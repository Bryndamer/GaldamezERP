<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inmuebles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descripcion');
            $table->decimal('precio', 12, 2)->index();
            $table->string('direccion');
            $table->unsignedInteger('habitaciones');
            $table->unsignedInteger('banos');
            $table->decimal('metraje', 8, 2);
            $table->enum('estado', ['disponible', 'vendido', 'reservado'])->default('disponible')->index();
            $table->enum('tipo', ['casa', 'apto', 'terreno'])->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inmuebles');
    }
};
