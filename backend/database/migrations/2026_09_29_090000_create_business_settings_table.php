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
        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->string('whatsapp_primary');
            $table->string('whatsapp_secondary')->nullable();
            $table->string('business_email')->nullable();
            $table->text('business_address')->nullable();
            $table->string('operating_hours_id');
            $table->string('operating_hours_en');
            $table->string('instagram_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_settings');
    }
};
