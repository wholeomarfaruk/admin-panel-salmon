<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Enums\ModelType;
use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use Illuminate\Http\Request;

class OurStoryController extends Controller
{
    public function ourStory(){
        $stories  = FileUpload::where('model_type', ModelType::OurStory->value)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'OurStory page found',
            'data' => $stories,
        ], 200);
    }
}
