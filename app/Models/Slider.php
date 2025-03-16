<?php

namespace App\Models;

use App\Enums\ModelType;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    public $fillable = [
        'name',
        'description',
    ];


    public function image()
    {
        return $this->hasOne(FileUpload::class,'model_id')->where('model_type', ModelType::Slider->value)->where('file_for', 'slider');
    }
}
