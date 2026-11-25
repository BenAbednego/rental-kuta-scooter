<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\GalleryImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqGalleryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_active_faqs_ordered(): void
    {
        Faq::create([
            "question_id" => "Pertanyaan 2",
            "answer_id" => "Jawaban 2",
            "question_en" => "Question 2",
            "answer_en" => "Answer 2",
            "order" => 2,
            "is_active" => true,
        ]);

        Faq::create([
            "question_id" => "Pertanyaan 1",
            "answer_id" => "Jawaban 1",
            "question_en" => "Question 1",
            "answer_en" => "Answer 1",
            "order" => 1,
            "is_active" => true,
        ]);

        Faq::create([
            "question_id" => "Pertanyaan Nonaktif",
            "answer_id" => "Jawaban Nonaktif",
            "question_en" => "Inactive Question",
            "answer_en" => "Inactive Answer",
            "order" => 3,
            "is_active" => false,
        ]);

        $response = $this->getJson("/api/faqs");

        $response->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "*" => ["id", "question_id", "answer_id", "question_en", "answer_en", "order", "is_active"],
                ],
            ]);

        $data = $response->json("data");
        $this->assertCount(2, $data);
        $this->assertEquals("Pertanyaan 1", $data[0]["question_id"]);
        $this->assertEquals("Pertanyaan 2", $data[1]["question_id"]);
    }

    public function test_can_fetch_active_gallery_images_ordered(): void
    {
        GalleryImage::create([
            "image_path" => "gallery/second.jpg",
            "caption" => "Foto Kedua",
            "order" => 2,
            "is_active" => true,
        ]);

        GalleryImage::create([
            "image_path" => "gallery/first.jpg",
            "caption" => "Foto Pertama",
            "order" => 1,
            "is_active" => true,
        ]);

        GalleryImage::create([
            "image_path" => "gallery/hidden.jpg",
            "caption" => "Foto Tersembunyi",
            "order" => 0,
            "is_active" => false,
        ]);

        $response = $this->getJson("/api/gallery");

        $response->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "*" => ["id", "image_path", "caption", "order", "is_active", "image_url"],
                ],
            ]);

        $data = $response->json("data");
        $this->assertCount(2, $data);
        $this->assertEquals("Foto Pertama", $data[0]["caption"]);
        $this->assertEquals("Foto Kedua", $data[1]["caption"]);
    }
}
