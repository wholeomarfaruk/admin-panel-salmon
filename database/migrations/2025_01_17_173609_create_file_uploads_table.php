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
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('file'); // File path
            $table->string('type')->nullable()->comment('File type');
            $table->tinyInteger('model_type')->nullable()->comment('1=> User, 2=> Property, 3=> Project, 4=> Blog');
            $table->unsignedBigInteger('model_id')->nullable()->comment('Related model row ID, NULL for general uploads');
            $table->string('file_for')->nullable();
            $table->integer('size')->nullable()->comment('File size in KB');
            $table->timestamps();

            $table->index(['model_type', 'model_id', 'file_for']); // Optimized Indexing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_uploads');
    }
};
