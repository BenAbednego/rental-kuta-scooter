<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                "question_id" => "Apa saja syarat untuk menyewa motor di Kuta?",
                "answer_id" => "Cukup membawa KTP (untuk WNI) atau Paspor (untuk WNA), SIM C / SIM Internasional yang masih berlaku, dan bukti reservasi hotel atau tiket pesawat di Bali.",
                "question_en" => "What are the requirements to rent a scooter in Kuta?",
                "answer_en" => "Valid Passport (for foreigners) or KTP (for locals), driver license / International Driving Permit, and your hotel booking or return flight ticket.",
                "order" => 1,
                "is_active" => true,
            ],
            [
                "question_id" => "Apakah bisa antar-jemput motor ke Bandara Ngurah Rai (DPS) atau hotel?",
                "answer_id" => "Bisa banget! Kami melayani antar-jemput unit gratis langsung ke hotel area Kuta, Legian, Seminyak, maupun Bandara Internasional Ngurah Rai (DPS).",
                "question_en" => "Do you provide delivery to Ngurah Rai Airport (DPS) or hotels?",
                "answer_en" => "Yes! We provide free delivery & pickup service directly to your hotel in Kuta, Legian, Seminyak, or at Ngurah Rai International Airport (DPS).",
                "order" => 2,
                "is_active" => true,
            ],
            [
                "question_id" => "Apa saja fasilitas yang sudah termasuk dalam harga sewa?",
                "answer_id" => "Setiap motor sudah termasuk 2 helm higienis berstandar SNI, 2 jas hujan, dan phone holder terpasang di stang motor.",
                "question_en" => "What is included with every scooter rental?",
                "answer_en" => "Every scooter comes with 2 sanitized SNI helmets, 2 sets of raincoats, and a secure phone holder mounted on the handlebars.",
                "order" => 3,
                "is_active" => true,
            ],
            [
                "question_id" => "Bagaimana sistem pembayaran dan deposit sewa?",
                "answer_id" => "Pembayaran bisa tunai atau transfer saat motor diserahkan. Deposit jaminan bersifat refundable dan akan langsung dikembalikan saat motor selesai disewa.",
                "question_en" => "How does payment and deposit work?",
                "answer_en" => "You can pay via cash or bank transfer upon scooter delivery. The refundable security deposit is returned immediately when the bike is handed back.",
                "order" => 4,
                "is_active" => true,
            ],
            [
                "question_id" => "Bagaimana jika motor mengalami kendala atau ban bocor di jalan?",
                "answer_id" => "Tim customer care kami siap siaga. Cukup hubungi via WhatsApp, tim kami akan meluncur memberikan bantuan mekanik atau unit motor pengganti.",
                "question_en" => "What happens if I get a flat tire or mechanical issue?",
                "answer_en" => "Our mobile support team is ready to help. Simply message us on WhatsApp, and we will send roadside assistance or a replacement bike right away.",
                "order" => 5,
                "is_active" => true,
            ],
        ];

        foreach ($faqs as $item) {
            Faq::updateOrCreate(
                ["question_id" => $item["question_id"]],
                $item
            );
        }
    }
}
