<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'admin1',
            'username' => 'admin1',
            'phone' => '1234567890',
            'password' => bcrypt('admin1')
        ]);

        User::create([
            'name' => 'user1',
            'username' => 'user1',
            'phone' => '1234567890',
            'password' => bcrypt('user1')
        ]);
    }
}
