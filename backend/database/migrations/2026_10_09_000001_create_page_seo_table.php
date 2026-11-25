<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_seo', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->string('title_id')->nullable();
            $table->string('title_en')->nullable();
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->string('featured_image')->nullable();
            $table->timestamps();
        });

        $now = now();
        DB::table('page_seo')->insert(
            collect(array_keys(config('seo_pages.pages')))
                ->map(fn (string $pageKey): array => [
                    'page_key' => $pageKey,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
                ->all()
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('page_seo');
    }
};
