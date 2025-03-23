<?php

namespace App\Models;

use App\Enums\ModelType;
use App\Enums\ProjectFileTypes;
use App\Enums\UploadFileTypes;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['id'];



    // protected $fillable = [
    //     'title',
    //     'sub_title',
    //     'status',
    //     'type',
    //     'start_date',
    //     'target_to_complete_date',
    //     'completion_date',
    //     'location',
    //     'landmark_lat_long',
    //     'project_extra_data',
    //     'videos',
    //     'description',
    //     'thumbnail',
    // ];
    public function video()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Project->value)->where('file_for', UploadFileTypes::Video->value);
    }
    public function files()
    {
        return $this->hasMany(FileUpload::class, 'model_id')
            ->where('model_type', ModelType::Project->value);
    }


    public function ataglanceimage()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Project->value)->where('file_for', ProjectFileTypes::ataglanceimage->value);
    }
    public function featured_image()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Project->value)->where('file_for', ProjectFileTypes::featured_image->value);
    }
    public function thumbnail()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Project->value)->where('file_for', ProjectFileTypes::thumbnail->value);
    }
    public function banner()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Project->value)->where('file_for', ProjectFileTypes::banner->value);
    }
    public function pdf()
    {
        return $this->hasOne(FileUpload::class, 'model_id')->where('model_type', ModelType::Project->value)->where('file_for', ProjectFileTypes::pdf->value);
    }
    public function amenities_images()
    {
        return $this->hasMany(FileUpload::class, 'model_id')->where('model_type', ModelType::Project->value)->where('file_for', ProjectFileTypes::amenities_images->value);
    }
    public function gallery()
    {
        return $this->hasMany(FileUpload::class, 'model_id')->where('model_type', ModelType::Project->value)->where('file_for', ProjectFileTypes::gallery->value);
    }
    // public function project_status()
    // {
    //     return $this->belongsTo(ProjectStatus::class,'status_id');
    // }
    // public function location(){
    //     return $this->belongsTo(Location::class);
    // }
}
