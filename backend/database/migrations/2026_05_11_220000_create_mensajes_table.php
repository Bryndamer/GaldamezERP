<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('email', 255)->index();
            $table->string('telefono', 20)->nullable();
            $table->text('mensaje');
            $table->foreignId('inmueble_id')->nullable()->constrained('inmuebles')->nullOnDelete();
            $table->enum('tipo', ['contacto', 'venta'])->default('contacto')->index();
            $table->boolean('leido')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
