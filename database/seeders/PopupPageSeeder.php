<?php

namespace Database\Seeders;

use App\Models\PopupPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PopupPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $popup = new PopupPage();
        $popup->is_popup_show = false;
        $popup->url_type = null;
        $popup->url = null;
        $popup->save();
    }
}
