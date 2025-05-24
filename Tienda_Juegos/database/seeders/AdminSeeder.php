<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
{
    \App\Models\Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('securepassword'),
        'email_verified_at' => now(),
    ]);
}
}
