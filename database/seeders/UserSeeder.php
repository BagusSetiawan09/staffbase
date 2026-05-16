<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Menjalankan proses pengisian data pengguna administrator utama.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Human Reasources',
            'email' => 'hr@staffbase.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}