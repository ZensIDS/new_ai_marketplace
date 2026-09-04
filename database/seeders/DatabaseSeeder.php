<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '628123456789',
        ]);

        // Customer contoh
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '628987654321',
        ]);

        // Kategori produk AI
        $categories = [
            'AI Chatbot & Asisten',
            'AI Penulis & Konten',
            'AI Gambar & Desain',
            'AI Video & Audio',
            'AI Coding & Developer Tools',
        ];
        $categoryModels = collect($categories)->map(fn ($name) => Category::create([
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(4),
        ]));

        // Produk (nama generik/fiktif — bukan brand pihak ketiga) + varian durasi langganan
        $products = [
            ['NovaChat AI Assistant', 'Asisten chatbot AI serbaguna untuk menjawab pertanyaan, brainstorming ide, dan menemani produktivitas harianmu.', 'AI Chatbot & Asisten'],
            ['Helio AI Support Bot', 'Chatbot AI siap pakai untuk kebutuhan customer service otomatis 24 jam non-stop.', 'AI Chatbot & Asisten'],
            ['ScriptGenius AI Writer', 'AI penulis konten untuk artikel, caption sosial media, dan copywriting dalam hitungan detik.', 'AI Penulis & Konten'],
            ['WordCraft AI Pro', 'Bantu proofreading, parafrase, dan optimasi SEO tulisan secara otomatis dengan AI.', 'AI Penulis & Konten'],
            ['PixelMind AI Studio', 'Generator gambar AI untuk membuat ilustrasi, desain, dan artwork dari deskripsi teks.', 'AI Gambar & Desain'],
            ['ArtVision AI Creator', 'Ubah ide desainmu jadi visual profesional secara instan dengan bantuan AI generatif.', 'AI Gambar & Desain'],
            ['ClipForge AI Video', 'Buat video pendek otomatis lengkap dengan voice over AI dan subtitle dari naskahmu.', 'AI Video & Audio'],
            ['SonicWave AI Audio', 'Text-to-speech & voice cloning AI berkualitas studio untuk konten audio kamu.', 'AI Video & Audio'],
            ['CodeGenius AI Assistant', 'AI pair-programmer untuk membantu menulis, melengkapi, dan debugging kode lebih cepat.', 'AI Coding & Developer Tools'],
            ['DevMate AI Reviewer', 'AI code reviewer otomatis untuk menjaga kualitas & keamanan kode sebelum production.', 'AI Coding & Developer Tools'],
        ];

        $variantTemplates = [
            ['name' => '1 Bulan', 'multiplier' => 1],
            ['name' => '3 Bulan', 'multiplier' => 2.7],
            ['name' => '1 Tahun', 'multiplier' => 9.5],
        ];

        foreach ($products as [$name, $desc, $catName]) {
            $category = $categoryModels->firstWhere('name', $catName);
            $basePrice = rand(45, 150) * 1000; // harga per bulan, kelipatan ribuan

            $product = Product::create([
                'category_id' => $category->id,
                'name' => $name,
                'slug' => Str::slug($name) . '-' . Str::random(5),
                'description' => $desc,
                'price' => $basePrice,
                'stock' => rand(10, 100),
                'is_active' => true,
            ]);

            foreach ($variantTemplates as $i => $tpl) {
                $product->variants()->create([
                    'name' => $tpl['name'],
                    'price' => round(($basePrice * $tpl['multiplier']) / 1000) * 1000,
                    'stock' => rand(5, 50),
                    'sort_order' => $i,
                ]);
            }
        }

        // Tag utama + kata terkait untuk demo semantic-like keyword expansion.
        $tagMap = [
            'AI' => 'artificial intelligence, generative ai, machine learning, intelligent',
            'Chatbot' => 'assistant, conversational ai, customer service, chat',
            'Writing' => 'writer, copywriting, article, content, text',
            'Image' => 'design, illustration, artwork, visual, generator',
            'Video' => 'clip, animation, subtitle, video generator',
            'Audio' => 'sound, music, voice, speech, podcast, audio processing',
            'Music' => 'audio, sound, song, melody, music generation, beat',
            'Voice' => 'audio, speech, text to speech, voice cloning, narration',
            'Coding' => 'programming, developer, code, software, debugging',
            'Generative AI' => 'gen ai, content generation, ai generator, generative',
        ];

        $tagModels = collect($tagMap)->mapWithKeys(function ($related, $name) {
            return [$name => Tag::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'related_keywords' => $related,
            ])];
        });

        // Hubungkan tag dengan produk demo berdasarkan konsepnya.
        foreach (Product::all() as $product) {
            $names = ['AI'];
            $category = strtolower($product->category->name ?? '');

            if (str_contains($category, 'chatbot')) $names[] = 'Chatbot';
            if (str_contains($category, 'penulis') || str_contains($product->name, 'Writer')) $names[] = 'Writing';
            if (str_contains($category, 'gambar') || str_contains($product->name, 'Studio') || str_contains($product->name, 'Creator')) $names[] = 'Image';
            if (str_contains($category, 'video')) $names[] = 'Video';
            if (str_contains($category, 'audio') || str_contains(strtolower($product->name), 'audio')) $names[] = 'Audio';
            if (str_contains(strtolower($product->name), 'music')) $names[] = 'Music';
            if (str_contains(strtolower($product->name), 'voice') || str_contains(strtolower($product->description), 'voice')) $names[] = 'Voice';
            if (str_contains($category, 'coding') || str_contains(strtolower($product->name), 'code')) $names[] = 'Coding';
            $names[] = 'Generative AI';

            $product->tags()->sync($tagModels->only(array_unique($names))->pluck('id')->all());
        }
    }
}
