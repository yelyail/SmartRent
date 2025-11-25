<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Account
        User::create([
            'first_name'  => 'Kem',
            'middle_name' => null,
            'last_name'   => 'Jin',
            'address'     => 'Davao City',
            'phone_num'   => '09123456789',
            'email'       => 'adminKem@smartrent.com',
            'role'        => UserRole::ADMIN, // Use enum here
            'password'    => Hash::make('administrator123!'),
            'status'      => 'active',
        ]);

        // Staff Account
        User::create([
            'first_name'  => 'Jake',
            'middle_name' => 'Mill',
            'last_name'   => 'Clown',
            'address'     => 'Davao City',
            'phone_num'   => '09987654321',
            'email'       => 'staffJake@smartrent.com',
            'role'        => UserRole::STAFF, // Use enum here
            'password'    => Hash::make('staffmember123!'),
            'status'      => 'active',
        ]);

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@smartrent.com / administrator123!');
        $this->command->info('Staff: staff@smartrent.com / staffmember123!');
    }
}