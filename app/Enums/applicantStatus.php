<?php

namespace App\Enums;

enum applicantStatus:int
{
    case Pending =0;
    case Accepted = 1;
    case Rejected = 2;
}
