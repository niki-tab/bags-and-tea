<?php

namespace Database\Seeders;

use App\Auth\User\Infrastructure\Eloquent\UserEloquentModel;
use App\Auth\Role\Infrastructure\Eloquent\RoleEloquentModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $adminUser = UserEloquentModel::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('bagsandtea1234'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Find admin role
        $adminRole = RoleEloquentModel::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('Admin role not found. Please run RoleSeeder first.');
            return;
        }

        // Attach admin role to user using pivot table
        if (!$adminUser->roles()->where('role_id', $adminRole->id)->exists()) {
            $adminUser->roles()->attach($adminRole->id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Admin user created successfully with admin role.');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Password: password');
    }
}