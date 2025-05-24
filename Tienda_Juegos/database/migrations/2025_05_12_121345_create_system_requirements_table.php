<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('system_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained();
            $table->enum('requirement_type', ['minimum', 'recommended']);
            $table->string('os');
            $table->string('processor');
            $table->string('memory');
            $table->string('graphics');
            $table->string('directx')->nullable();
            $table->string('storage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_requirements');
    }
};
