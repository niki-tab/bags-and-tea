<?php

namespace Database\Seeders;

use App\Auth\Role\Infrastructure\Eloquent\RoleEloquentModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => Str::uuid()->toString(),
                'name' => 'admin',
                'display_name' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'buyer',
                'display_name' => 'Buyer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'vendor',
                'display_name' => 'Vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            RoleEloquentModel::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }

        $this->command->info('Default roles created successfully.');
    }
}