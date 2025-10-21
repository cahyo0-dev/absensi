<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@system.local',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Sample pengawas user
        User::create([
            'name' => 'Pengawas Contoh',
            'email' => 'pengawas@system.local',
            'password' => Hash::make('pengawas123'),
            'role' => 'pengawas',
            'is_active' => true,
        ]);

        $this->command->info('User admin dan pengawas berhasil dibuat!');
        $this->command->info('Admin: admin@system.local / admin123');
        $this->command->info('Pengawas: pengawas@system.local / pengawas123');
    }
}