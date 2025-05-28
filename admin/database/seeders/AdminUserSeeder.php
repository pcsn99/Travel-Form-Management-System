<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'slpxuloyola2025@gmail.com',
            'password' => Hash::make('TFSLP@2025'), 
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

