<?php

namespace App\Console\Commands;

use Modules\Users\Entities\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    protected $signature = 'admin:assign {user_id : The ID of the user to assign admin role}';
    protected $description = 'Assign admin role to a user';

    public function handle(): void
    {
        $userId = $this->argument('user_id');
        $adminRole = Role::where('name', 'admin')->first();
        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        if (!$adminRole) {
            $this->error('Admin role not found.');
            return;
        }

        $user->assignRole($adminRole);
        $this->info("Successfully assigned admin role to user {$userId}.");
    }
}
