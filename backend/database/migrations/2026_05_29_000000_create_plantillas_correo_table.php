<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantillas_correo', function (Blueprint $table) {
            $table->id();
            $table->string('identificador', 50)->unique();
            $table->string('nombre', 100);
            $table->string('asunto', 255);
            $table->string('saludo', 255)->nullable();
            $table->text('cuerpo_principal');
            $table->text('cuerpo_secundario')->nullable();
            $table->string('firma', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantillas_correo');
    }
};
