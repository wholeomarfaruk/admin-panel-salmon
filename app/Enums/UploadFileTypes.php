<?php

namespace App\Enums;

enum UploadFileTypes: string
{
    case Image = "image";
    case PDF = 'pdf';
    case Video = 'video';
    case Audio = 'audio';
    case Gif = 'gif';
    case Text = 'text';
    case Docs = 'docs';

}
