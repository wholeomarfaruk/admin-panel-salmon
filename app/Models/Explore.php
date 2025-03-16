<?php

namespace App\Models;

use App\Enums\ModelType;
use App\Enums\UploadFileTypes;
use Illuminate\Database\Eloquent\Model;

class Explore extends Model
{
    public function project(){
        return $this->belongsTo(Project::class,"project_id");
    }
    public function video()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Explore->value)->where('file_for', UploadFileTypes::Video->value);
    }
}
