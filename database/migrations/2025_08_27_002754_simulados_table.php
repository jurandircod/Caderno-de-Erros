<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('simulados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedInteger('questions_count')->default(0);
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('wrong_count')->default(0);
            $table->unsignedInteger('time_seconds')->nullable(); // tempo total definido para o simulado em segundos
            $table->unsignedInteger('duration_seconds')->nullable(); // tempo gasto de verdade
            $table->json('categories')->nullable(); // categorias filtradas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('simulados');
    }
};
