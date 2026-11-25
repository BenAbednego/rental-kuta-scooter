<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('image')->nullable();
            $table->json('slides')->nullable();
            $table->unsignedSmallInteger('slide_interval')->default(5);
            $table->string('video_source')->nullable();
            $table->string('video_path')->nullable();
            $table->string('youtube_id', 11)->nullable();
            $table->string('poster')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_settings');
    }
};
