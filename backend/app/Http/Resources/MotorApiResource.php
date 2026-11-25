<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class MotorApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $mainImageUrl = $this->main_image
            ? (Str::startsWith($this->main_image, ['http://', 'https://'])
                ? $this->main_image
                : asset('storage/'.$this->main_image))
            : null;

        $galleryUrls = is_array($this->gallery_images)
            ? array_map(function ($img) {
                return Str::startsWith($img, ['http://', 'https://'])
                    ? $img
                    : asset('storage/'.$img);
            }, $this->gallery_images)
            : [];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'slug' => $this->slug,
            'category' => $this->category,
            'cc' => $this->cc,
            'description_id' => $this->description_id,
            'description_en' => $this->description_en,
            'price_daily' => (float) $this->price_daily,
            'price_weekly' => $this->price_weekly ? (float) $this->price_weekly : null,
            'price_monthly' => $this->price_monthly ? (float) $this->price_monthly : null,
            'deposit_amount' => $this->deposit_amount ? (float) $this->deposit_amount : null,
            'unit_count' => (int) $this->unit_count,
            'main_image' => $mainImageUrl,
            'gallery_images' => $galleryUrls,
            'status' => $this->status,
            'specs' => $this->specs ?? [],
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
