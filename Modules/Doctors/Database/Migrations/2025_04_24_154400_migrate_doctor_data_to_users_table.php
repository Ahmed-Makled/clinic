<?php

namespace Modules\Doctors\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Modules\Users\Entities\User;
use Modules\Doctors\Entities\Doctor;
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
        // Ensure the Doctor role exists
        $doctorRole = Role::firstOrCreate(['name' => 'Doctor']);

        // Get all doctors without a user_id
        $doctors = DB::table('doctors')->whereNull('user_id')->get();

        foreach ($doctors as $doctor) {
            // Check if email already exists in users table
            $emailExists = DB::table('users')->where('email', $doctor->email)->exists();

            $email = $doctor->email;
            if ($emailExists) {
                // Add a timestamp to the email to ensure uniqueness
                $parts = explode('@', $doctor->email);
                $email = $parts[0] . '+' . time() . '@' . $parts[1];
            }

            // Create a new user for this doctor
            $userId = DB::table('users')->insertGetId([
                'name' => $doctor->name,
                'email' => $email,
                'password' => $doctor->password ?? bcrypt('password'), // Use doctor's password if exists, otherwise set a default
                'phone_number' => $doctor->phone ?? null,
                'type' => 'doctor',
                'created_at' => now(),
            ]);

            // Update the doctor's user_id
            DB::table('doctors')
                ->where('id', $doctor->id)
                ->update(['user_id' => $userId]);

            // Assign Doctor role to the user
            $user = User::find($userId);
            if ($user) {
                $user->assignRole($doctorRole);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Get all doctors with a user_id
        $doctors = DB::table('doctors')->whereNotNull('user_id')->get();

        foreach ($doctors as $doctor) {
            // Delete the associated user
            DB::table('users')->where('id', $doctor->user_id)->delete();

            // Set the doctor's user_id to null
            DB::table('doctors')
                ->where('id', $doctor->id)
                ->update(['user_id' => null]);
        }
    }
};
