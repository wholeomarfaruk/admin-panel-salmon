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
        //     'name' => 'asifulmamun',
        //     'email' => 'asifulmamun@gmail.com',
        // ]);

        User::factory()->create([
            'name' => 'salmon',
            'email' => 'salmon@salmondevelopersbd.com',
            'password' => bcrypt('#Salmon2025'),
        ]);

        $this->call([
            SettingsTableSeeder::class,
            LocationSeeder::class,
            ProjectStatusSeeder::class,
            StatsSeeder::class,
            OurStorySeeder::class,
        ]);


    }
}
