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
        Schema::create('motors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->string('category', 50)->default('matic');
            $table->integer('cc')->nullable();
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('price_daily', 10, 2);
            $table->decimal('price_weekly', 10, 2)->nullable();
            $table->decimal('price_monthly', 10, 2)->nullable();
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->integer('unit_count')->default(1);
            $table->string('main_image', 255)->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('status', 20)->default('active');
            $table->json('specs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motors');
    }
};
