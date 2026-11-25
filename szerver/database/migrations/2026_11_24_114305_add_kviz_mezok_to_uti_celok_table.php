<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uti_celok', function (Blueprint $table) {
            $table->string('szezon')->nullable();
            $table->string('koltseg', 10)->nullable();
            $table->json('kviz_cimkek')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('uti_celok', function (Blueprint $table) {
            $table->dropColumn(['szezon', 'koltseg', 'kviz_cimkek']);
        });
    }
};