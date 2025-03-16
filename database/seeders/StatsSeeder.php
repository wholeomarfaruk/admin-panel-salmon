<?php

namespace Database\Seeders;

use App\Enums\ModelType;
use App\Helpers\FileHelper;
use App\Models\Stats;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stats::create([
            "id" => "1",
            "title" => "where Dreams Take Shape and Memories are Made",
            "stats" => json_encode([
                ["title" => "buildings", "number" => "20", "number_prefix" => "M"],
                ["title" => "Happy Clients", "number" => "20", "number_prefix" => "M"],
                ["title" => "Awards", "number" => "20", "number_prefix" => "M"],
                ["title" => "buildings", "number" => "20", "number_prefix" => "M"],
                ["title" => "Happy Clients", "number" => "20", "number_prefix" => "M"],
                ["title" => "Awards", "number" => "20", "number_prefix" => "M"],
            ])
        ]);
        FileHelper::uploadFile("uploads/home/stats/1.jpg","stats",ModelType::Stats->value,1,[
            "name"=> "Stats",
            "type"=> "image",
            "size"=> "0",
        ]);

    }
}
