<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uti_celok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orszag_id')->constrained('orszagok')->cascadeOnDelete();
            $table->string('nev');
            $table->text('leiras')->nullable();
            $table->string('kep')->nullable();
            $table->unsignedInteger('napi_ar')->nullable();
            $table->unsignedSmallInteger('repulesi_ido_perc')->nullable();
            $table->string('idojaras')->nullable();
            $table->string('cimke')->nullable();
            $table->boolean('nepszeru')->default(false);
            $table->timestamps();
        });

        Schema::create('uti_cel_cimke', function (Blueprint $table) {
            $table->foreignId('uti_cel_id')->constrained('uti_celok')->cascadeOnDelete();
            $table->foreignId('cimke_id')->constrained('cimkek')->cascadeOnDelete();
            $table->primary(['uti_cel_id', 'cimke_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uti_cel_cimke');
        Schema::dropIfExists('uti_celok');
    }
};