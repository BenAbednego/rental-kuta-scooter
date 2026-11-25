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
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->string('name_id', 100);
            $table->string('name_en', 100);
            $table->string('description_id', 255)->nullable();
            $table->string('description_en', 255)->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('price_type', 20)->default('per_day'); // 'per_day' or 'per_rental'
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};
