<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Disable foreign key checks to prevent potential constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Predefined roles to create
        $roles = ['Patient', 'Doctor', 'Admin'];

        foreach ($roles as $roleName) {
            // Check if role already exists with empty guard
            $existingRole = Role::where('name', $roleName)->where('guard_name', '')->first();

            if (!$existingRole) {
                Role::create([
                    'name' => $roleName,
                    'guard_name' => '' // Use default (empty) guard
                ]);
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Remove the seeded roles
        $roles = ['Patient', 'Doctor', 'Admin'];
        foreach ($roles as $roleName) {
            Role::where('name', $roleName)->where('guard_name', '')->delete();
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};

