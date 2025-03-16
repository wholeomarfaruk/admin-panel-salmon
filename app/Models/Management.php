<?php

namespace App\Models;

use App\Enums\ManagementFileTypes;
use App\Enums\ModelType;
use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'description',
        'order',
    ];
    public function files()
    {
        return $this->hasMany(FileUpload::class, 'model_id')
            ->where('model_type', ModelType::Management->value);
    }


    public function image()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Management->value)->where('file_for', ManagementFileTypes::image->value);
    }
}
