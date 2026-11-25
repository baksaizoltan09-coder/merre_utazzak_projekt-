<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orszagok', function (Blueprint $table) {
            $table->id();
            $table->string('nev');
            $table->string('kod', 2)->nullable();
            $table->string('kontinens')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orszagok');
    }
};