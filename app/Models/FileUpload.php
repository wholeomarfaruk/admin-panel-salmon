<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileUpload extends Model
{
    use HasFactory;

    protected $table = 'file_uploads';

    protected $guarded = ['id'];        

    // protected $fillable = [
    //     'status',
    //     'type',
    //     'file',
    //     'used',
    //     'name',
    // ];
}
