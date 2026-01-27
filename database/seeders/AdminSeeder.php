<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Kesantrian',
            'email' => 'admin@santri.com',
            'password' => Hash::make('password123'), // Passwordnya ini
        ]);
    }
}
