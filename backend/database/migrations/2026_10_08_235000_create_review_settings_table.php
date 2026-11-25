<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_settings', function (Blueprint $table) {
            $table->id();
            $table->string('display_mode')->default('manual');
            $table->text('iframe_src')->nullable();
            $table->string('iframe_provider')->nullable();
            $table->string('iframe_title')->nullable();
            $table->unsignedInteger('iframe_width')->nullable();
            $table->unsignedInteger('iframe_height')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_settings');
    }
};
