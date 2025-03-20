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
        Schema::table('projects', function (Blueprint $table) {

                // Modify existing columns to be nullable
                $table->string('flat_number')->nullable()->change();
                $table->string('land_area')->nullable()->change();
                $table->string('facing_land')->nullable()->change();
                $table->string('floor_number')->nullable()->change();
                $table->string('front_road')->nullable()->change();
                $table->string('square_ft')->nullable()->change();
                $table->string('num_car_parking')->nullable()->change();
                $table->string('building_type')->nullable()->change();
                $table->string('bed_bath_balcony_lift')->nullable()->change();
                $table->string("location")->nullable()->change();
                $table->string("project_status")->nullable()->change();

                // Remove the project_type column
                $table->dropColumn('project_type');
                $table->dropColumn('video');




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
             // Revert columns back to NOT NULL
             $table->string('flat_number')->nullable(false)->change();
             $table->string('land_area')->nullable(false)->change();
             $table->string('facing_land')->nullable(false)->change();
             $table->string('floor_number')->nullable(false)->change();
             $table->string('front_road')->nullable(false)->change();
             $table->string('square_ft')->nullable(false)->change();
             $table->string('num_car_parking')->nullable(false)->change();
             $table->string('building_type')->nullable(false)->change();
             $table->string('bed_bath_balcony_lift')->nullable(false)->change();
             $table->string("location")->nullable(false)->change();
             $table->string("project_status")->nullable(false)->change();



             // Restore the removed column
             $table->tinyInteger('project_type')->index()->comment('1: Commercial, 2: Residential');
             $table->text('video')->nullable();
        });
    }
};
