<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
            'Fitness',
            'Fishing',
            'Gaming',
            'Swimming',
            'Reading',
            'Sports',
            'Music',
            'Travel',
            'Art',
            'Technology',
            'Food',
            'Photography',
        ];

        foreach ($interests as $interest) {
            Interest::create(['interest' => $interest]);
        }
    }
}
