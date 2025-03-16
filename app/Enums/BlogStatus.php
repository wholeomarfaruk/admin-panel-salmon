<?php

namespace App\Enums;

enum BlogStatus:int
{
    case PUBLISHED = 1;
    case DRAFT = 0;
}
