<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\ClinicTestDataSeeder;

class SeedClinicTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clinic:seed-test-data
                            {--force : Force seeding without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed clinic with test data: 20 specialties, 50 doctors, 50 patients, 20 appointments with ratings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏥 Clinic Test Data Seeder');
        $this->info('=========================');
        $this->info('This will create:');
        $this->info('• 20 medical specialties');
        $this->info('• 50 doctors with complete profiles and schedules');
        $this->info('• 50 patients with complete profiles');
        $this->info('• 20 appointments (past and future)');
        $this->info('• Ratings for completed appointments');
        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm('Do you want to proceed?', false)) {
                $this->error('❌ Operation cancelled.');
                return 1;
            }
        }

        $this->info('🚀 Starting data seeding...');

        try {
            $seeder = new ClinicTestDataSeeder();
            $seeder->setCommand($this);
            $seeder->run();

            $this->newLine();
            $this->info('✅ Test data seeded successfully!');
            $this->newLine();
            $this->info('📊 Summary:');
            $this->info('• Specialties: 20 medical specialties created');
            $this->info('• Doctors: 50 doctors with profiles and schedules');
            $this->info('• Patients: 50 patients with complete profiles');
            $this->info('• Appointments: 20 appointments created');
            $this->info('• Ratings: Auto-generated for completed appointments');
            $this->newLine();
            $this->info('🔑 Test Credentials:');
            $this->info('• Doctor emails: doctor1@clinic.com to doctor50@clinic.com');
            $this->info('• Patient emails: patient1@clinic.com to patient50@clinic.com');
            $this->info('• All passwords: password123');
            $this->newLine();
            $this->info('🎯 You can now test the clinic system with this data!');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error occurred during seeding: ' . $e->getMessage());
            return 1;
        }
    }
}
