<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class CheckUnusedCode extends Command
{
    protected $signature = 'check:unused-code {--delete : Delete unused files after confirmation}';
    protected $description = 'Check and optionally delete unused Models, Controllers, Migrations, and Entities';

    protected $ignored = [
        'Controller', 'BaseController', 'AuthController',
        'Model', 'BaseModel', 'User',
        'BaseEntity', 'Entity',
        'Migration',
    ];

    protected $codeExtensions = ['php', 'blade.php', 'js', 'vue', 'json'];

    public function handle()
    {
        $paths = [
            'Models' => app_path('Models'),
            'Controllers' => app_path('Http/Controllers'),
            'Migrations' => database_path('migrations'),
        ];

        if (File::isDirectory(app_path('Entities'))) {
            $paths['Entities'] = app_path('Entities');
        } elseif (File::isDirectory(base_path('Entities'))) {
            $paths['Entities'] = base_path('Entities');
        }

        $unusedFiles = [];
        $filesChecked = 0;

        $this->info("Scanning project for unused files...");
        $bar = $this->output->createProgressBar(count($this->getAllTargetFiles($paths)));

        foreach ($paths as $type => $path) {
            if (!File::isDirectory($path)) {
                $this->warn("Directory not found: $path");
                continue;
            }

            $files = File::allFiles($path);

            foreach ($files as $file) {
                $filesChecked++;
                $className = pathinfo($file, PATHINFO_FILENAME);

                if ($type === 'Migrations' && preg_match('/^\d{4}_\d{2}_\d{2}_\d{6}_/', $className)) {
                    $className = $this->extractMigrationClassName($className);
                }

                if (in_array($className, $this->ignored)) {
                    $bar->advance();
                    continue;
                }

                $isUsed = $this->isFileUsed($className, $file, $type);

                if (!$isUsed) {
                    $unusedFiles[] = [
                        'type' => $type,
                        'name' => $className,
                        'path' => $file->getPathname(),
                        'reason' => $this->getUnusedReason($file, $type)
                    ];
                }
                $bar->advance();
            }
        }

        $bar->finish();
        $this->line('');

        $this->info("Analysis complete! Checked $filesChecked files.");

        if (empty($unusedFiles)) {
            $this->info("✅ No unused files detected.");
            return 0;
        }

        $this->warn("⚠️  Found " . count($unusedFiles) . " potentially unused files:");
        $this->table(['Type', 'Name', 'Path', 'Reason'], $unusedFiles);

        if ($this->option('delete')) {
            if ($this->confirm('❗ Are you sure you want to delete these files?')) {
                foreach ($unusedFiles as $file) {
                    File::delete($file['path']);
                    $this->line("🗑️ Deleted: " . $file['path']);
                }
                $this->info("✅ Unused files deleted.");
            } else {
                $this->info("❌ Deletion cancelled.");
            }
        }

        return 0;
    }

    private function extractMigrationClassName($filename)
    {
        $parts = explode('_', $filename, 5);
        if (count($parts) >= 5) {
            $descriptiveName = $parts[4];
            return Str::studly($descriptiveName);
        }
        return $filename;
    }

    private function getAllTargetFiles($paths)
    {
        $allFiles = [];
        foreach ($paths as $path) {
            if (File::isDirectory($path)) {
                $allFiles = array_merge($allFiles, File::allFiles($path));
            }
        }
        return $allFiles;
    }

    private function getUnusedReason($file, $type)
    {
        if ($type === 'Models') {
            return "No references to this model in code or migrations";
        } elseif ($type === 'Controllers') {
            return "No routes or other code references to this controller";
        } elseif ($type === 'Migrations') {
            return "Migration may have been replaced or is no longer needed";
        } elseif ($type === 'Entities') {
            return "No references to this entity in the codebase";
        }
        return "No references found";
    }

    private function isFileUsed($className, $fileObj, $type)
    {
        $filePath = $fileObj->getPathname();
        $fileContent = File::get($filePath);
        $namespace = $this->extractNamespace($fileContent);
        $fullClassName = $namespace . '\\' . $className;

        $searchTerms = [$className];
        if ($type === 'Controllers' && !Str::endsWith($className, 'Controller')) {
            $searchTerms[] = $className . 'Controller';
        }

        if ($type === 'Migrations') {
            $tableName = $this->getTableNameFromMigration($fileContent);
            if ($tableName) {
                $searchTerms[] = $tableName;
            }
        }

        if ($type === 'Models') {
            $tableName = Str::snake(Str::plural($className));
            $searchTerms[] = $tableName;

            $definedTable = $this->getTableNameFromModel($fileContent);
            if ($definedTable) {
                $searchTerms[] = $definedTable;
            }
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path()));
        $regexIterator = new RegexIterator($iterator, '/\.(' . implode('|', $this->codeExtensions) . ')$/i');

        foreach ($regexIterator as $file) {
            $currentFilePath = $file->getPathname();

            if ($currentFilePath === $filePath) {
                continue;
            }

            if (Str::contains($currentFilePath, '/vendor/')) {
                continue;
            }

            $content = File::get($currentFilePath);

            foreach ($searchTerms as $term) {
                if (str_contains($content, $term)) {
                    return true;
                }
            }

            if (str_contains($content, $fullClassName) ||
                str_contains($content, str_replace('\\', '\\\\', $fullClassName))) {
                return true;
            }
        }

        return false;
    }

    private function extractNamespace($fileContent)
    {
        if (preg_match('/namespace\s+([^;]+)/i', $fileContent, $matches)) {
            return trim($matches[1]);
        }
        return '';
    }

    private function getTableNameFromMigration($fileContent)
    {
        if (preg_match('/Schema::create\([\'"]([^\'"]+)[\'"]/i', $fileContent, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function getTableNameFromModel($fileContent)
    {
        if (preg_match('/protected\s+\$table\s*=\s*[\'"]([^\'"]+)[\'"]/i', $fileContent, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
