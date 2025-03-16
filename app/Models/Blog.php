<?php

namespace App\Models;

use App\Enums\BlogFileTypes;
use App\Enums\ModelType;
use Illuminate\Database\Eloquent\Model;
use App\Models\FileUpload;

class Blog extends Model
{
    protected $guarded = ['id'];

    public function files()
    {
        return $this->hasMany(FileUpload::class, 'model_id')
            ->where('model_type', ModelType::Blog->value);
    }


    public function thumbnail()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Blog->value)->where('file_for', BlogFileTypes::thumbnail->value);
    }


    public function gallery()
    {
        return $this->hasMany(FileUpload::class, 'model_id')->where('model_type', ModelType::Blog->value)->where('file_for', BlogFileTypes::gallery->value);
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

}
