<?php

namespace App\Enums;

enum ProjectFileTypes: string
{
    case banner = 'banner';
    case thumbnail = 'thumbnail';
    case gallery = 'gallery';
    case amenities_images = 'amenities_images';
    case pdf = 'pdf';
}
