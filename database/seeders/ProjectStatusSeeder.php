<?php

namespace Database\Seeders;

use App\Models\ProjectStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectStatuses = [
            'Handed Over',
            'Under Construction',
            'Sale Ongoing',
            'Upcoming',
            'Under Constraction',
        ];
        foreach ($projectStatuses as $status) {
            ProjectStatus::create([
                'name'=> $status,
                'slug' => Str::slug($status),
            ]);
        }



    }
}
