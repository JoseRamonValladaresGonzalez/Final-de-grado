<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('games', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description');
    $table->decimal('price', 8, 2);
    $table->decimal('discount', 5, 2)->nullable();
    $table->string('developer');
    $table->string('publisher');
    $table->date('release_date');
    $table->text('features')->nullable(); // JSON
    $table->text('requirements')->nullable(); // JSON
    $table->string('main_image');
    $table->string('header_image');
    $table->timestamps();
});
