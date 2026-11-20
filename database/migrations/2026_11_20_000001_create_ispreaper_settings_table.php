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
        Schema::create('isp_reaper_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('url')->nullable();
            $table->integer('check_interval')->default(300);
            $table->integer('download_timeout')->default(600);
            $table->time('start_time')->default('02:00:00');
            $table->time('end_time')->default('04:00:00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('isp_reaper_settings');
    }
};