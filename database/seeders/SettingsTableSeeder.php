<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::insert([
            ['key' => 'site_name', 'value' => 'My Laravel App'],
            ['key' => 'default_language', 'value' => 'en'],
            ['key' => 'timezone', 'value' => 'UTC'],
            ['key' => 'TotalAreaBuilt', 'value' => '10'],
            ['key' => 'NumberofProjects', 'value' => '10'],
            ['key' => 'YearsSinceInception', 'value' => '10'],
            ['key' => 'HappyClients', 'value' => '10'],

        ]);
    }
}
