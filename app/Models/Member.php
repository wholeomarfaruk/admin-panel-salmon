<?php

namespace App\Models;

use App\Enums\MemberFileTypes;
use App\Enums\ModelType;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $guarded = ['id'];

    public function files()
    {
        return $this->hasMany(FileUpload::class, 'model_id')
            ->where('model_type', ModelType::Member->value);
    }


    public function image()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Member->value)->where('file_for', MemberFileTypes::image->value);
    }
}
