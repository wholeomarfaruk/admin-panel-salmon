<?php

namespace App\Models;

use App\Enums\ModelType;
use Illuminate\Database\Eloquent\Model;

class PopupPage extends Model
{
    public function files()
    {
        return $this->hasMany(FileUpload::class, 'model_id')
            ->where('model_type', ModelType::Popup->value);
    }

    public function image()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Popup->value)->where('file_for', "image");
    }
}
