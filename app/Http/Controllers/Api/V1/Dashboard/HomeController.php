<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Enums\ModelType;
use App\Http\Controllers\Controller;
use App\Models\Explore;
use App\Models\FileUpload;
use App\Models\Project;
use App\Models\Stats;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function homePage(){
        $slider = FileUpload::where('model_type', ModelType::Home->value)
        ->where('file_for', 'slider')
        ->latest()
        ->get();

        $featuredProjects = Project::with('banner', 'thumbnail','pdf','amenities_images','gallery')->where('is_featured', "1")->get();

        foreach ($featuredProjects as  $item) {
            $item->link = url("projects/{$item->slug}");
        }

        $exploreItems = Explore::with(['video', 'project' => function($q) {
            $q->with('banner', 'thumbnail', 'pdf', 'amenities_images', 'gallery');
        }])->latest()->get();

        foreach ($exploreItems as $exploreItem) {
            if ($exploreItem->project) {
            $exploreItem->project->link = url("projects/" . $exploreItem->project->slug);
            }
        }
        $stats = Stats::with('image')->get();
        $data = [
            'status'=> 'success',
            'message' => 'home page found',
            'data' => [
                'slider'=> $slider,
                'featuredProjects'=> $featuredProjects,
                'explore'=> $exploreItems,
                'stats'=> $stats
            ],
        ];
        return response()->json($data);
    }
}
