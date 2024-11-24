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
        Schema::create('admin_confirmation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('requisicao_id')->on('requisicoes')->onDelete('cascade');
            $table->foreignId('admin_id')->on('users')->onDelete('cascade');
            $table->string('status')->default('em confirmação');
            $table->string('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
