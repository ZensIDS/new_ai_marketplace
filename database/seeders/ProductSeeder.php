<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Seeder produk langganan AI (layanan asli, total 100 produk).
 *
 * Harga acuan = harga paket bulanan (billing bulanan) di situs resmi masing-masing
 * layanan per awal Oktober 2026, dalam USD, lalu dikonversi ke rupiah memakai $kurs.
 * Harga vendor sering berubah, jadi cek ulang sebelum dijual dan ubah angka USD di
 * bawah (atau $kurs) kalau perlu.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $kurs = 17700; // Rp per 1 USD (acuan kurs awal September 2026)

        // ---------- Tag (+ kata terkait untuk keyword expansion) ----------
        $tagMap = [
            'AI'                => 'artificial intelligence, generative ai, machine learning, intelligent',
            'Generative AI'     => 'gen ai, content generation, ai generator, generative',
            'Chatbot'           => 'assistant, asisten, conversational ai, customer service, chat',
            'Writing'           => 'writer, copywriting, article, content, text, menulis, tulisan',
            'Image'             => 'design, illustration, artwork, visual, generator, gambar, foto',
            'Design'            => 'desain, logo, branding, mockup, creative, poster',
            'Video'             => 'clip, animation, subtitle, video generator, film, klip',
            'Audio'             => 'sound, music, voice, speech, podcast, audio processing, suara',
            'Music'             => 'audio, sound, song, melody, music generation, beat, musik, lagu',
            'Voice'             => 'audio, speech, text to speech, voice cloning, narration, voice over, suara',
            'Coding'            => 'programming, developer, code, software, debugging, ngoding, pemrograman',
            'No-Code'           => 'vibe coding, app builder, website builder, tanpa coding, bikin aplikasi, bikin website',
            'Productivity'      => 'produktivitas, workflow, automation, efficiency, kerja, office',
            'Business'          => 'bisnis, sales, finance, hr, enterprise, umkm, kantor',
            'Marketing'         => 'ads, iklan, social media, campaign, seo, promosi',
            'Research'          => 'riset, paper, jurnal, academic, literature, summary, skripsi, penelitian',
            'Search'            => 'pencarian, search engine, mesin pencari, cari informasi, sitasi',
            'Education'         => 'belajar, tutor, kuis, sekolah, kuliah, learning, pelajar, mahasiswa',
            'Language Learning' => 'belajar bahasa, bahasa inggris, bahasa asing, speaking, kosakata',
            'Translation'       => 'terjemahan, translate, bahasa, multilingual, language, penerjemah',
            'Presentation'      => 'slide, powerpoint, deck, pitch, presentasi',
            'Meeting'           => 'rapat, notulen, meeting notes, zoom, notulensi',
            'Transcription'     => 'speech to text, transkrip, rekaman, subtitle, transkripsi',
        ];

        $tags = collect($tagMap)->mapWithKeys(fn ($related, $name) => [
            $name => Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'related_keywords' => $related]
            ),
        ]);

        // ---------- Kategori ----------
        $categoryNames = [
            'chat'         => 'AI Chatbot & Asisten',
            'writing'      => 'AI Penulis & Konten',
            'image'        => 'AI Gambar & Desain',
            'video'        => 'AI Video & Animasi',
            'music'        => 'AI Musik & Audio',
            'code'         => 'AI Coding & Developer Tools',
            'productivity' => 'AI Produktivitas & Bisnis',
            'research'     => 'AI Riset & Edukasi',
        ];

        $categories = collect($categoryNames)->map(fn ($name) => Category::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name]
        ));

        // ---------- Produk ----------
        // [nama, kategori, harga bulanan USD, deskripsi, tag tambahan]
        $products = [
            // === Chatbot & Asisten ===
            ['ChatGPT Go', 'chat', 8, 'Paket hemat ChatGPT dari OpenAI untuk pemakaian harian: tanya jawab, menulis, dan belajar dengan batas pemakaian lebih longgar dari paket gratis.', ['Chatbot', 'Writing']],
            ['ChatGPT Plus', 'chat', 20, 'Paket andalan ChatGPT dari OpenAI: model terbaru, pembuatan gambar, analisis file, Deep Research, dan Codex untuk coding.', ['Chatbot', 'Writing', 'Image', 'Coding', 'Research']],
            ['ChatGPT Pro', 'chat', 200, 'Paket tertinggi ChatGPT dari OpenAI dengan batas pemakaian terbesar dan akses ke model penalaran tercanggih untuk pekerjaan berat.', ['Chatbot', 'Coding', 'Research', 'Image']],
            ['Claude Pro', 'chat', 20, 'Asisten AI dari Anthropic untuk menulis, menganalisis dokumen, dan riset, lengkap dengan Projects serta akses Claude Code untuk coding.', ['Chatbot', 'Writing', 'Coding', 'Research']],
            ['Claude Max 5x', 'chat', 100, 'Paket Claude dengan batas pemakaian 5 kali lipat dari Pro, cocok untuk pengguna harian yang sering kehabisan limit.', ['Chatbot', 'Coding', 'Research']],
            ['Google AI Pro', 'chat', 19.99, 'Paket AI Google dengan Gemini versi unggulan, konteks panjang, pembuatan video Veo, dan integrasi ke Gmail, Docs, serta layanan Google lainnya.', ['Chatbot', 'Image', 'Video', 'Research', 'Productivity']],
            ['SuperGrok', 'chat', 30, 'Paket AI dari xAI dengan akses Grok yang lebih luas, termasuk data real-time dari X, untuk tanya jawab dan riset.', ['Chatbot', 'Image', 'Research']],
            ['Claude Max 20x', 'chat', 200, 'Paket Claude dengan batas pemakaian 20 kali lipat dari Pro, untuk pengguna berat dan developer yang bekerja seharian dengan Claude Code.', ['Chatbot', 'Coding', 'Research']],
            ['Google AI Ultra', 'chat', 249.99, 'Paket tertinggi Google dengan akses terluas ke Gemini, Veo, dan fitur AI eksperimental, plus penyimpanan sangat besar.', ['Chatbot', 'Video', 'Image', 'Research']],
            ['SuperGrok Heavy', 'chat', 300, 'Paket tertinggi xAI dengan akses Grok Heavy dan batas pemakaian terbesar untuk penalaran dan riset berat.', ['Chatbot', 'Research', 'Coding']],
            ['Le Chat Pro', 'chat', 14.99, 'Asisten AI dari Mistral (Prancis) dengan batas pemakaian lebih besar, pencarian web, riset mendalam, dan pembuatan gambar.', ['Chatbot', 'Writing', 'Research']],
            ['Poe Subscription', 'chat', 19.99, 'Satu langganan untuk mengakses banyak model AI (GPT, Claude, Gemini, dan lainnya) beserta bot buatan komunitas dalam satu aplikasi.', ['Chatbot', 'Writing', 'Image']],
            ['Character.AI c.ai+', 'chat', 9.99, 'Ngobrol dan roleplay dengan karakter AI, dengan antrean lebih cepat dan fitur tambahan dibanding versi gratis.', ['Chatbot']],

            // === Penulis & Konten ===
            ['Grammarly Pro', 'writing', 30, 'Asisten menulis AI untuk cek tata bahasa, gaya penulisan, dan penulisan ulang, terintegrasi di browser dan aplikasi favoritmu.', ['Writing', 'Productivity']],
            ['QuillBot Premium', 'writing', 19.95, 'Alat parafrase, cek tata bahasa, peringkas teks, dan pengecek plagiarisme dalam satu paket premium.', ['Writing', 'Education']],
            ['Jasper Pro', 'writing', 69, 'Platform AI untuk tim marketing: brand voice, copywriting, dan konten kampanye yang konsisten.', ['Writing', 'Marketing', 'Business']],
            ['Sudowrite', 'writing', 10, 'Partner menulis fiksi berbasis AI untuk membangun cerita, karakter, dan melanjutkan naskah yang buntu.', ['Writing']],
            ['Rytr', 'writing', 9, 'Penulis AI ringan untuk caption, email, dan artikel pendek dengan banyak template dan pilihan nada bahasa.', ['Writing', 'Marketing']],
            ['Writesonic Standard', 'writing', 49, 'Platform AI untuk menulis artikel SEO, iklan, dan konten pemasaran, dilengkapi pelacakan visibilitas merek di mesin pencari AI.', ['Writing', 'Marketing']],
            ['Wordtune Premium', 'writing', 24.99, 'Asisten menulis AI untuk menulis ulang kalimat, mengubah nada, dan meringkas teks dengan hasil yang natural.', ['Writing', 'Productivity']],
            ['ProWritingAid Premium', 'writing', 30, 'Cek tata bahasa dan gaya menulis mendalam dengan laporan analisis, cocok untuk penulis buku dan penulis profesional.', ['Writing']],
            ['Jenni AI Unlimited', 'writing', 20, 'Asisten menulis akademik dengan sitasi otomatis, untuk menyusun esai, makalah, dan skripsi.', ['Writing', 'Research', 'Education']],
            ['Originality.ai Pro', 'writing', 14.95, 'Pendeteksi konten AI dan pengecek plagiarisme untuk editor, penerbit, dan pemilik situs.', ['Writing', 'Marketing']],
            ['Anyword Starter', 'writing', 49, 'Penulis AI untuk copy iklan dan marketing dengan skor prediksi performa untuk setiap versi tulisan.', ['Writing', 'Marketing', 'Business']],

            // === Gambar & Desain ===
            ['Midjourney Basic', 'image', 10, 'Generator gambar AI dengan kualitas artistik yang terkenal. Paket paling terjangkau dengan jatah GPU cepat bulanan.', ['Image', 'Design']],
            ['Midjourney Standard', 'image', 30, 'Paket Midjourney paling populer dengan jatah GPU cepat lebih besar dan mode Relax tanpa batas untuk gambar.', ['Image', 'Design']],
            ['Midjourney Pro', 'image', 60, 'Jatah GPU cepat lebih besar dan Stealth Mode untuk menyembunyikan hasil generate dari galeri publik.', ['Image', 'Design']],
            ['Adobe Firefly Standard', 'image', 9.99, 'AI generatif Adobe yang aman untuk penggunaan komersial: gambar, vektor, dan video singkat dengan kredit generatif bulanan.', ['Image', 'Design', 'Video']],
            ['Adobe Firefly Pro', 'image', 19.99, 'Kredit generatif dua kali lipat Standard, plus Adobe Express Premium dan Photoshop versi web dan mobile.', ['Image', 'Design', 'Video']],
            ['Leonardo AI Essential', 'image', 12, 'Platform AI gambar dan video dengan kontrol detail, token harian, dan fitur melatih model sendiri.', ['Image', 'Design', 'Video']],
            ['Leonardo AI Premium', 'image', 30, 'Jatah token bulanan lebih besar untuk produksi gambar dan video AI dengan volume tinggi.', ['Image', 'Design', 'Video']],
            ['Ideogram Plus', 'image', 20, 'Generator gambar AI yang unggul dalam menampilkan teks pada gambar, cocok untuk poster, logo, dan materi promosi.', ['Image', 'Design']],
            ['Freepik Premium', 'image', 24, 'Akses aset stok premium plus Freepik AI Suite untuk membuat gambar dan video dari berbagai model AI.', ['Image', 'Design', 'Video']],
            ['Krea Pro', 'image', 35, 'Generator gambar dan video AI dengan pembuatan real-time, upscaling, dan banyak model dalam satu platform.', ['Image', 'Design', 'Video']],
            ['Magnific AI Pro', 'image', 39, 'Upscaler dan enhancer gambar AI yang menambah detail resolusi tinggi pada foto dan hasil generate.', ['Image', 'Design']],
            ['PhotoRoom Pro', 'image', 12.99, 'Editor foto AI untuk hapus background dan foto produk profesional, populer untuk penjual online.', ['Image', 'Design', 'Marketing']],
            ['Playground AI Pro', 'image', 15, 'Platform desain gambar AI dengan kanvas untuk membuat poster, logo, dan konten media sosial.', ['Image', 'Design']],

            // === Video & Animasi ===
            ['Runway Standard', 'video', 15, 'Studio video AI generatif (Gen-4) untuk membuat klip sinematik dan efek visual dari teks atau gambar.', ['Video']],
            ['Runway Pro', 'video', 35, 'Kredit jauh lebih banyak dari Standard dan akses API untuk produksi video AI yang lebih intens.', ['Video']],
            ['Kling AI Standard', 'video', 10, 'Generator video AI dengan gerakan manusia yang realistis, populer karena kualitas sinematik dengan harga terjangkau.', ['Video']],
            ['Luma Dream Machine Plus', 'video', 30, 'Generator video AI yang cepat dan sinematik dengan kontrol gerakan kamera, lisensi komersial termasuk.', ['Video']],
            ['Pika Standard', 'video', 8, 'Generator video AI untuk konten sosial media dengan efek kreatif seperti Pikaffects dan Pikaswaps.', ['Video', 'Marketing']],
            ['HeyGen Creator', 'video', 29, 'Presenter avatar AI dan terjemahan video sekali klik untuk video marketing dan konten personal.', ['Video', 'Voice', 'Translation', 'Marketing']],
            ['Synthesia Starter', 'video', 29, 'Video presenter AI dengan avatar yang berbicara dalam 140+ bahasa untuk pelatihan dan komunikasi bisnis.', ['Video', 'Business', 'Education']],
            ['Pika Pro', 'video', 28, 'Jatah kredit video jauh lebih besar dari Standard, dengan akses model terbaru dan lisensi komersial.', ['Video', 'Marketing']],
            ['Kling AI Pro', 'video', 37, 'Kredit lebih banyak untuk membuat video AI sinematik dengan kualitas tinggi dan fitur lanjutan.', ['Video']],
            ['Runway Unlimited', 'video', 95, 'Generasi video tanpa batas dalam mode Explore, untuk kreator dan studio dengan volume produksi tinggi.', ['Video']],
            ['Luma Dream Machine Lite', 'video', 9.99, 'Paket awal untuk membuat video AI sinematik dari teks atau gambar dengan harga terjangkau.', ['Video']],
            ['InVideo AI Plus', 'video', 28, 'Buat video lengkap dengan narasi, stok footage, dan subtitle hanya dari satu prompt teks.', ['Video', 'Voice', 'Marketing']],
            ['Opus Clip Starter', 'video', 15, 'Ubah video panjang atau podcast menjadi klip pendek siap upload untuk TikTok, Reels, dan Shorts.', ['Video', 'Marketing', 'Transcription']],
            ['Descript Creator', 'video', 24, 'Edit video dan audio seperti mengedit dokumen teks, dengan transkripsi otomatis dan penghapus kata pengisi.', ['Video', 'Audio', 'Transcription']],

            // === Musik & Audio ===
            ['Suno Pro', 'music', 10, 'Buat lagu lengkap dengan vokal hanya dari prompt teks, termasuk hak komersial pada paket Pro.', ['Music', 'Audio', 'Voice']],
            ['Suno Premier', 'music', 30, 'Kredit sangat besar, ekspor stem, dan Suno Studio (DAW) untuk produser musik.', ['Music', 'Audio', 'Voice']],
            ['ElevenLabs Starter', 'music', 6, 'Text-to-speech realistis dan voice cloning instan dengan lisensi komersial untuk narasi dan voice over.', ['Voice', 'Audio']],
            ['ElevenLabs Creator', 'music', 22, 'Voice cloning profesional, kredit lebih besar, dan kualitas audio lebih tinggi untuk kreator konten.', ['Voice', 'Audio']],
            ['ElevenLabs Pro', 'music', 99, 'Kredit besar dan keluaran audio 44,1 kHz via API untuk produksi audio skala agensi.', ['Voice', 'Audio', 'Business']],
            ['Murf Creator', 'music', 29, 'Studio voice over AI dengan banyak suara natural untuk video, presentasi, dan e-learning.', ['Voice', 'Audio', 'Education']],
            ['Udio Standard', 'music', 10, 'Generator lagu AI dengan vokal dan kualitas audio tinggi dari prompt teks, dengan jatah kredit bulanan.', ['Music', 'Audio', 'Voice']],
            ['Udio Pro', 'music', 30, 'Kredit jauh lebih besar dan fitur lanjutan untuk musisi dan produser yang membuat lagu dalam jumlah banyak.', ['Music', 'Audio', 'Voice']],
            ['Soundraw Creator', 'music', 16.99, 'Generator musik latar AI bebas royalti untuk video, podcast, dan konten kreator, dengan genre dan suasana yang bisa diatur.', ['Music', 'Audio']],
            ['Moises Premium', 'music', 24.99, 'Pisahkan vokal dan instrumen dari lagu, plus pengatur tempo dan kunci nada, cocok untuk latihan dan produksi musik.', ['Music', 'Audio']],
            ['LALAL.AI Lite', 'music', 15, 'Pemisah stem audio berbasis AI untuk mengekstrak vokal, drum, bass, dan instrumen dari rekaman.', ['Music', 'Audio']],
            ['Speechify Premium', 'music', 29, 'Aplikasi text-to-speech dengan suara natural untuk membacakan dokumen, buku, dan artikel, serta fitur voice over.', ['Voice', 'Audio', 'Education']],

            // === Coding & Developer Tools ===
            ['Cursor Pro', 'code', 20, 'Editor kode berbasis AI dengan mode Agent, edit multi-file, dan Tab completion tanpa batas.', ['Coding']],
            ['Cursor Pro Plus', 'code', 60, 'Kredit model premium 3 kali lipat dari Pro untuk developer yang memakai agent seharian.', ['Coding']],
            ['GitHub Copilot Pro', 'code', 10, 'Asisten coding dari GitHub: saran kode inline, chat, coding agent, dan code review langsung di IDE.', ['Coding']],
            ['GitHub Copilot Pro Plus', 'code', 39, 'Jatah premium request dan pilihan model jauh lebih besar dari Pro untuk pemakaian berat.', ['Coding']],
            ['Lovable Pro', 'code', 25, 'Bangun aplikasi web penuh hanya dengan prompt, lengkap dengan hosting, custom domain, dan editing kode.', ['Coding', 'No-Code']],
            ['Bolt.new Pro', 'code', 25, 'Bangun dan deploy aplikasi web serta mobile dari prompt dengan jatah 10 juta token per bulan.', ['Coding', 'No-Code']],
            ['Replit Core', 'code', 20, 'Lingkungan coding di cloud dengan Replit Agent untuk membuat, menjalankan, dan men-deploy aplikasi dari browser.', ['Coding', 'No-Code']],
            ['Windsurf Pro', 'code', 15, 'Editor kode berbasis AI dengan agent Cascade untuk edit multi-file dan pemahaman konteks seluruh proyek.', ['Coding']],
            ['v0 Premium', 'code', 20, 'Generator antarmuka web dari Vercel yang mengubah prompt menjadi komponen React dan Next.js siap deploy.', ['Coding', 'No-Code', 'Design']],
            ['JetBrains AI Pro', 'code', 10, 'Asisten AI terintegrasi di IDE JetBrains (IntelliJ, PyCharm, WebStorm) untuk chat, completion, dan refactoring.', ['Coding']],
            ['Amazon Q Developer Pro', 'code', 19, 'Asisten coding dari AWS untuk menulis, men-debug, dan memodernisasi kode, dengan pemahaman mendalam layanan AWS.', ['Coding', 'Business']],
            ['Devin Core', 'code', 20, 'Software engineer AI otonom dari Cognition yang mengerjakan tugas coding dari awal hingga pull request.', ['Coding']],
            ['Warp Build', 'code', 20, 'Terminal modern dengan agent AI untuk menjalankan perintah, memperbaiki error, dan mengotomasi pekerjaan developer.', ['Coding', 'Productivity']],
            ['Base44 Starter', 'code', 20, 'Bangun aplikasi web lengkap dengan database dan login hanya lewat prompt, tanpa perlu menulis kode.', ['Coding', 'No-Code']],

            // === Produktivitas & Bisnis ===
            ['Microsoft 365 Premium', 'productivity', 19.99, 'Microsoft 365 dengan Copilot di Word, Excel, PowerPoint, dan Outlook, plus penyimpanan OneDrive hingga 6 TB untuk maksimal 6 orang.', ['Productivity', 'Presentation', 'Writing', 'Business']],
            ['Notion AI (Business)', 'productivity', 24, 'Workspace Notion dengan AI penuh: Notion Agent, AI Meeting Notes, dan pencarian di seluruh workspace.', ['Productivity', 'Business', 'Meeting']],
            ['Gamma Plus', 'productivity', 9, 'Buat presentasi, dokumen, dan website dari satu prompt dengan AI, tanpa badge Gamma.', ['Presentation', 'Productivity', 'Design']],
            ['Otter.ai Pro', 'productivity', 16.99, 'Transkripsi dan ringkasan meeting otomatis dengan jatah 1.200 menit per bulan.', ['Meeting', 'Transcription', 'Productivity']],
            ['Canva Pro', 'productivity', 15, 'Desain grafis dengan Magic Studio AI, template premium, dan Brand Kit untuk konten dan presentasi.', ['Design', 'Image', 'Presentation', 'Marketing']],
            ['Google AI Plus', 'productivity', 7.99, 'Paket AI Google yang terjangkau: akses Gemini lebih luas dari paket gratis plus penyimpanan tambahan.', ['Productivity', 'Chatbot', 'Image']],
            ['Zapier Professional', 'productivity', 29.99, 'Hubungkan ribuan aplikasi dan otomasi pekerjaan, dengan AI untuk membuat alur kerja dari perintah teks.', ['Productivity', 'Business']],
            ['Make Core', 'productivity', 10.59, 'Platform otomasi visual untuk menghubungkan aplikasi dan membangun alur kerja serta agen AI.', ['Productivity', 'Business']],
            ['Fireflies.ai Pro', 'productivity', 19, 'Perekam dan pencatat meeting otomatis dengan transkrip, ringkasan, dan pencarian di seluruh rapat.', ['Meeting', 'Transcription', 'Productivity']],
            ['Fathom Premium', 'productivity', 20, 'Pencatat meeting AI untuk Zoom, Meet, dan Teams dengan ringkasan otomatis dan sinkronisasi ke CRM.', ['Meeting', 'Transcription', 'Business']],
            ['Taskade Pro', 'productivity', 16, 'Workspace tugas, catatan, dan mind map dengan agen AI untuk kolaborasi tim.', ['Productivity', 'Business']],
            ['Superhuman Starter', 'productivity', 30, 'Aplikasi email super cepat dengan AI untuk menulis balasan, meringkas, dan mengelola inbox.', ['Productivity', 'Business', 'Writing']],
            ['Beautiful.ai Pro', 'productivity', 45, 'Pembuat presentasi dengan AI yang menata slide secara otomatis agar desain tetap rapi dan konsisten.', ['Presentation', 'Design', 'Business']],
            ['Motion Pro', 'productivity', 34, 'Kalender dan manajer tugas AI yang menjadwalkan ulang pekerjaan secara otomatis sesuai prioritas.', ['Productivity', 'Business']],

            // === Riset & Edukasi ===
            ['Perplexity Pro', 'research', 20, 'Mesin pencari AI dengan jawaban bersitasi, Deep Research harian, dan pilihan model dari OpenAI, Anthropic, dan Google.', ['Search', 'Research', 'Chatbot']],
            ['Perplexity Max', 'research', 200, 'Paket tertinggi Perplexity: Labs tanpa batas, kredit Computer bulanan, dan fitur Model Council untuk riset berat.', ['Search', 'Research']],
            ['SciSpace Premium', 'research', 20, 'Asisten riset AI untuk mencari, membaca, dan merangkum jurnal ilmiah serta tanya jawab langsung dengan paper.', ['Research', 'Education']],
            ['DeepL Pro', 'research', 10.49, 'Penerjemah AI yang akurat untuk teks dan dokumen, dengan keamanan data lebih baik dari versi gratis.', ['Translation', 'Writing']],
            ['Duolingo Super', 'research', 12.99, 'Belajar bahasa asing lewat pelajaran bergamifikasi tanpa iklan dan tanpa batas energi.', ['Language Learning', 'Education']],
            ['Elicit Plus', 'research', 12, 'Asisten riset AI yang mencari dan mengekstrak data dari paper ilmiah untuk tinjauan literatur.', ['Research', 'Education']],
            ['Consensus Premium', 'research', 11.99, 'Mesin pencari AI untuk jurnal ilmiah yang menjawab pertanyaan berdasarkan temuan penelitian.', ['Search', 'Research', 'Education']],
            ['Quizlet Plus', 'research', 7.99, 'Flashcard dan latihan belajar dengan fitur AI untuk membuat kuis dan materi belajar dari catatan.', ['Education']],
            ['Speak Premium', 'research', 20, 'Tutor bahasa AI untuk latihan percakapan langsung dengan umpan balik, populer untuk belajar bahasa Inggris.', ['Language Learning', 'Education']],
        ];

        // ---------- Logo produk ----------
        // File SVG (monogram per brand) dibuat otomatis ke storage/app/public/products/{brand}.svg.
        // Nilai kolom image = 'products/{brand}.svg', sesuai Product::image_url -> asset('storage/' . $image).
        // Pastikan sudah menjalankan: php artisan storage:link
        $brands = [
            'ChatGPT',
            'Claude',
            'Google AI',
            'SuperGrok',
            'Le Chat',
            'Poe',
            'Character.AI',
            'Grammarly',
            'QuillBot',
            'Jasper',
            'Sudowrite',
            'Rytr',
            'Writesonic',
            'Wordtune',
            'ProWritingAid',
            'Jenni',
            'Originality',
            'Anyword',
            'Midjourney',
            'Adobe Firefly',
            'Leonardo',
            'Ideogram',
            'Freepik',
            'Krea',
            'Magnific',
            'PhotoRoom',
            'Playground',
            'Runway',
            'Kling',
            'Luma',
            'Pika',
            'HeyGen',
            'Synthesia',
            'InVideo',
            'Opus Clip',
            'Descript',
            'Suno',
            'Udio',
            'ElevenLabs',
            'Murf',
            'Soundraw',
            'Moises',
            'LALAL',
            'Speechify',
            'Cursor',
            'GitHub Copilot',
            'Lovable',
            'Bolt',
            'Replit',
            'Windsurf',
            'v0',
            'JetBrains',
            'Amazon Q',
            'Devin',
            'Warp',
            'Base44',
            'Microsoft 365',
            'Notion',
            'Gamma',
            'Otter',
            'Canva',
            'Zapier',
            'Make',
            'Fireflies',
            'Fathom',
            'Taskade',
            'Superhuman',
            'Beautiful.ai',
            'Motion',
            'Perplexity',
            'SciSpace',
            'DeepL',
            'Duolingo',
            'Elicit',
            'Consensus',
            'Quizlet',
            'Speak',
        ];

        $customInitials = [
            'Microsoft 365' => '365', 'ChatGPT' => 'GPT', 'Character.AI' => 'C.AI', 'Beautiful.ai' => 'B.ai',
            'Make' => 'Mk', 'v0' => 'v0', 'LALAL' => 'LL', 'Midjourney' => 'MJ', 'ElevenLabs' => '11',
            'Le Chat' => 'LC', 'Adobe Firefly' => 'Ff', 'Opus Clip' => 'OC', 'Amazon Q' => 'AQ',
            'GitHub Copilot' => 'GH', 'Google AI' => 'G',
        ];

        $palette = [
            '#10A37F', '#D97757', '#4285F4', '#111827', '#7C3AED', '#E11D48', '#0EA5E9', '#F59E0B',
            '#059669', '#DB2777', '#2563EB', '#EA580C', '#0D9488', '#9333EA', '#475569', '#65A30D',
        ];

        $logoFor = function (string $name) use ($brands, $customInitials, $palette): ?string {
            foreach ($brands as $brand) {
                if (! str_starts_with($name, $brand)) {
                    continue;
                }

                $words = explode(' ', $brand);
                $initials = $customInitials[$brand] ?? (count($words) >= 2
                    ? strtoupper($words[0][0] . $words[1][0])
                    : strtoupper($brand[0]) . strtolower($brand[1]));

                $size = strlen($initials) <= 2 ? 110 : (strlen($initials) === 3 ? 84 : 64);
                $color = $palette[crc32($brand) % count($palette)];
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 256 256">'
                    . '<rect width="256" height="256" rx="56" fill="' . $color . '"/>'
                    . '<text x="128" y="128" text-anchor="middle" dominant-baseline="central" '
                    . 'font-family="Arial,Helvetica,sans-serif" font-weight="700" font-size="' . $size . '" fill="#fff">'
                    . htmlspecialchars($initials) . '</text></svg>';

                $path = 'products/' . Str::slug($brand) . '.svg';
                Storage::disk('public')->put($path, $svg);

                return $path;
            }

            return null;
        };

        $variantTemplates = [
            ['name' => '1 Bulan', 'multiplier' => 1],
            ['name' => '3 Bulan', 'multiplier' => 2.9],
            ['name' => '1 Tahun', 'multiplier' => 10],
        ];

        foreach ($products as [$name, $categoryKey, $priceUsd, $description, $extraTags]) {
            $basePrice = round(($priceUsd * $kurs) / 1000) * 1000; // harga per bulan, kelipatan ribuan

            $product = Product::create([
                'category_id' => $categories[$categoryKey]->id,
                'name'        => $name,
                'slug'        => Str::slug($name),
                'description' => $description,
                'price'       => $basePrice,
                'stock'       => rand(10, 100),
                'image'       => $logoFor($name),
                'is_active'   => true,
            ]);

            foreach ($variantTemplates as $i => $tpl) {
                $product->variants()->create([
                    'name'       => $tpl['name'],
                    'price'      => round(($basePrice * $tpl['multiplier']) / 1000) * 1000,
                    'stock'      => rand(5, 50),
                    'sort_order' => $i,
                ]);
            }

            $tagNames = array_unique(array_merge(['AI', 'Generative AI'], $extraTags));
            $product->tags()->sync($tags->only($tagNames)->pluck('id')->all());
        }
    }
}