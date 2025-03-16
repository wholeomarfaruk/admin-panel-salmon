<?php

namespace App\Models;

use App\Enums\ModelType;
use App\Enums\UploadFileTypes;
use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    public function files()
    {
        return $this->hasMany(FileUpload::class, 'model_id')
            ->where('model_type', ModelType::Stats->value);
    }


    public function image()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Stats->value)->where('file_for', 'stats');
    }
}
