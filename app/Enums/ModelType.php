<?php

namespace App\Enums;

enum ModelType:int
{
    case Project = 1;
    case Blog = 2;
    case Member = 3;
    case Management = 4;
    case Home = 5;
    case Explore = 6;
    case Stats = 7;
    case OurStory = 8;
    case Slider = 9;
}
