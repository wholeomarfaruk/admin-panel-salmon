<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Enums\ModelType;
use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use App\Models\Slider;
use Illuminate\Http\Request;

class WhySalmonController extends Controller
{
    public function whySalmon(){
        $content  = Slider::with('image')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Why Salmon page found',
            'data' => $content,
        ], 200);
    }
}
