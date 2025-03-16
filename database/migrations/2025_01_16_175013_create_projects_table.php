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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')->unique()->index();
            $table->string('flat_number')->index();
            $table->string('land_area')->index();
            $table->string('facing_land')->index();
            $table->string('floor_number')->index();
            $table->string('front_road')->index();
            $table->string('square_ft')->index();
            $table->string('num_car_parking')->index();
            $table->string('building_type')->index();
            $table->string('bed_bath_balcony_lift')->index();
            $table->string("location")->index();
            $table->string("project_status")->index();
            // $table->string('pdf')->index()->default('#'); // transer to file upload
             //banner
             //thumbnail
             //gallery
             //amenities_images
            $table->string('description')->nullable();
            $table->string('map_data')->nullable();
            $table->enum('is_featured', [0, 1])->default(0)->comment('0: No, 1: Yes');
            $table->text('video')->nullable();

            // $table->unsignedBigInteger('location_id')->index();
            // $table->unsignedBigInteger('status_id')->index();
            $table->tinyInteger('project_type')->index()->comment('1: Commercial, 2: Residential');

            $table->timestamps();

            // $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            // $table->foreign('status_id')->references('id')->on('project_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
