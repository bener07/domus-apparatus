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
        Schema::create('requisicoes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('quantity')->default(1);
            $table->string('token')->nullable();
            $table->string('aditionalInfo')->nullable();
            $table->string('status')->default('pendente');
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->dateTime('entrega_real')->nullable();
            $table->foreignId('discipline_id')->on('disciplines')->onDelete('cascade');
            $table->foreignId('room_id')->on('classrooms')->onDelete('cascade');
            $table->foreignId('admin_id')->on('users')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_users');
    }
};
