<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Enums\ModelType;
use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use Illuminate\Http\Request;

class WhySalmonController extends Controller
{
    public function whySalmon(){
        $content  = FileUpload::where('model_type', ModelType::Slider->value)
        ->where('file_for', 'slider')
        ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Why Salmon page found',
            'data' => $content,
        ], 200);
    }
}
