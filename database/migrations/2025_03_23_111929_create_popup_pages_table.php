<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('popup_pages', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_popup_show')->default(0)->comment('0 = No, 1 = Yes');
            $table->string('url_type')->nullable();
            $table->string('url')->nullable();
            //image linked file upload model

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popup_pages');
    }
};
