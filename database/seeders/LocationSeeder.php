<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $locations = [
            'North Badda',
            'Sanarpar',
            'Basundhara RA',
            'West Ulon, Rampura',
            'East Badda',
            'Tusar Dhara Residence Area, Sign Board',
        ];

        foreach ($locations as $location) {

            Location::create([
                'name'=> $location,
                'slug' => Str::slug($location),
            ]);

        }
    }
}
