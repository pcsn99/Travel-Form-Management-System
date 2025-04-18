<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CommunityMembersSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('member123'); // common password for all

        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Community Member {$i}",
                'email' => "member{$i}@example.com",
                'password' => $password,
                'role' => 'member',
            ]);
        }
    }
}

