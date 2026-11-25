<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("pricing_packages", function (Blueprint $table) {
            $table->id();
            $table->string("name_id");
            $table->string("name_en");
            $table->string("subtitle_id")->nullable();
            $table->string("subtitle_en")->nullable();
            $table->decimal("price", 12, 2);
            $table->string("price_unit_id");
            $table->string("price_unit_en");
            $table->json("benefits_id");
            $table->json("benefits_en");
            $table->string("icon")->default("Bike");
            $table->string("cta_label_id");
            $table->string("cta_label_en");
            $table->boolean("is_featured")->default(false);
            $table->integer("order")->default(0);
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("pricing_packages");
    }
};
