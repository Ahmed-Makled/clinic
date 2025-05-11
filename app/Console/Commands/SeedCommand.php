<?php

namespace App\Console\Commands;

use Illuminate\Database\Console\Seeds\SeedCommand as BaseSeedCommand;
use Symfony\Component\Console\Input\InputOption;

class SeedCommand extends BaseSeedCommand
{
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $parentOptions = parent::getOptions();

        // Add our custom fake option
        $parentOptions[] = ['fake', null, InputOption::VALUE_NONE, 'Seed the database with fake data as well'];

        return $parentOptions;
    }
}
