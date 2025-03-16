<?php

namespace Database\Seeders;

use App\Enums\ModelType;
use App\Models\FileUpload;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OurStorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            [
                "name" => "foundation Image",
                "type" => "image",
                "size" => "0",
                "file" => "#",
                'file_for' => "foundation_image",
                'model_type' => ModelType::OurStory->value,
                'model_id' => null,
            ],
            [
                "name" => "mision Image",
                "type" => "image",
                "size" => "0",
                "file" => "#",
                'file_for' => "mision_image",
                'model_type' => ModelType::OurStory->value,
                'model_id' => null,
            ],
            [
                "name" => "vision Image",
                "type" => "image",
                "size" => "0",
                "file" => "#",
                'file_for' => "vision_image",
                'model_type' => ModelType::OurStory->value,
                'model_id' => null,
            ],
            [
                "name" => "values Image",
                "type" => "image",
                "size" => "0",
                "file" => "#",
                'file_for' => "values_image",
                'model_type' => ModelType::OurStory->value,
                'model_id' => null,
            ],
        ];
        FileUpload::insert($images);

    }
}
