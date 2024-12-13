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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('details');
            $table->string('status')->default('disponivel');
            $table->foreignId('requisicao_id')->on('requisicoes')->onDelete('cascade')->nullable();
            $table->foreignId('base_id')->on('base_products')->onDelete('cascade');
            $table->string("isbn")->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
