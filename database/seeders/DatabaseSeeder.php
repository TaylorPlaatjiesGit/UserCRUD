<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'surname'  => 'User',
            'email'    => 'admin@mail.com',
            'password' => bcrypt('secret123')
        ]);

        $this->call([
            InterestSeeder::class,
            LanguageSeeder::class,
        ]);
    }
}
