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
        Schema::create('classroom_disciplines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->on('classrooms')->onDelete('cascade');
            $table->foreignId('discipline_id')->constrained()->on('disciplines')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_disciplines');
    }
};
