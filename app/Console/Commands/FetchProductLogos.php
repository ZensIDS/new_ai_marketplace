<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Unduh logo asli tiap brand dan isi kolom products.image.
 *
 * Pakai: php artisan products:fetch-logos
 * Brand yang gagal diunduh dibiarkan memakai gambar lama (monogram), jadi tidak ada gambar yang kosong.
 */
class FetchProductLogos extends Command
{
    protected $signature = 'products:fetch-logos {--only= : Hanya brand ini, contoh: --only=Claude}';

    protected $description = 'Unduh logo asli brand produk ke storage/app/public/products dan update kolom image';

    private array $brands = [
        'ChatGPT' => 'chatgpt.com', 'Claude' => 'claude.ai', 'Google AI' => 'gemini.google.com',
        'SuperGrok' => 'grok.com', 'Le Chat' => 'chat.mistral.ai', 'Poe' => 'poe.com',
        'Character.AI' => 'character.ai', 'Grammarly' => 'grammarly.com', 'QuillBot' => 'quillbot.com',
        'Jasper' => 'jasper.ai', 'Sudowrite' => 'sudowrite.com', 'Rytr' => 'rytr.me',
        'Writesonic' => 'writesonic.com', 'Wordtune' => 'wordtune.com', 'ProWritingAid' => 'prowritingaid.com',
        'Jenni' => 'jenni.ai', 'Originality' => 'originality.ai', 'Anyword' => 'anyword.com',
        'Midjourney' => 'midjourney.com', 'Adobe Firefly' => 'firefly.adobe.com', 'Leonardo' => 'leonardo.ai',
        'Ideogram' => 'ideogram.ai', 'Freepik' => 'freepik.com', 'Krea' => 'krea.ai',
        'Magnific' => 'magnific.ai', 'PhotoRoom' => 'photoroom.com', 'Playground' => 'playground.com',
        'Runway' => 'runwayml.com', 'Kling' => 'klingai.com', 'Luma' => 'lumalabs.ai', 'Pika' => 'pika.art',
        'HeyGen' => 'heygen.com', 'Synthesia' => 'synthesia.io', 'InVideo' => 'invideo.io',
        'Opus Clip' => 'opus.pro', 'Descript' => 'descript.com', 'Suno' => 'suno.com', 'Udio' => 'udio.com',
        'ElevenLabs' => 'elevenlabs.io', 'Murf' => 'murf.ai', 'Soundraw' => 'soundraw.io',
        'Moises' => 'moises.ai', 'LALAL' => 'lalal.ai', 'Speechify' => 'speechify.com',
        'Cursor' => 'cursor.com', 'GitHub Copilot' => 'github.com', 'Lovable' => 'lovable.dev',
        'Bolt' => 'bolt.new', 'Replit' => 'replit.com', 'Windsurf' => 'windsurf.com', 'v0' => 'v0.dev',
        'JetBrains' => 'jetbrains.com', 'Amazon Q' => 'aws.amazon.com', 'Devin' => 'devin.ai',
        'Warp' => 'warp.dev', 'Base44' => 'base44.com', 'Microsoft 365' => 'microsoft365.com',
        'Notion' => 'notion.so', 'Gamma' => 'gamma.app', 'Otter' => 'otter.ai', 'Canva' => 'canva.com',
        'Zapier' => 'zapier.com', 'Make' => 'make.com', 'Fireflies' => 'fireflies.ai',
        'Fathom' => 'fathom.video', 'Taskade' => 'taskade.com', 'Superhuman' => 'superhuman.com',
        'Beautiful.ai' => 'beautiful.ai', 'Motion' => 'usemotion.com', 'Perplexity' => 'perplexity.ai',
        'SciSpace' => 'scispace.com', 'DeepL' => 'deepl.com', 'Duolingo' => 'duolingo.com',
        'Elicit' => 'elicit.com', 'Consensus' => 'consensus.app', 'Quizlet' => 'quizlet.com',
        'Speak' => 'speak.com',
    ];

    private array $extensions = [
        'image/png' => 'png', 'image/svg+xml' => 'svg', 'image/jpeg' => 'jpg', 'image/webp' => 'webp',
        'image/x-icon' => 'ico', 'image/vnd.microsoft.icon' => 'ico', 'image/gif' => 'gif',
    ];

    public function handle(): int
    {
        $only = $this->option('only');
        $failed = [];

        foreach ($this->brands as $brand => $domain) {
            if ($only && strcasecmp($only, $brand) !== 0) {
                continue;
            }

            $sources = [
                "https://icon.horse/icon/{$domain}",
                "https://icons.duckduckgo.com/ip3/{$domain}.ico",
                "https://www.google.com/s2/favicons?domain={$domain}&sz=128",
                "https://favicon.im/{$domain}?larger=true",
            ];

            $saved = null;

            foreach ($sources as $url) {
                try {
                    $res = Http::timeout(15)->withHeaders(['User-Agent' => 'Mozilla/5.0'])->get($url);
                } catch (\Throwable $e) {
                    continue;
                }

                $mime = strtolower(trim(explode(';', $res->header('Content-Type') ?? '')[0]));

                if (! $res->successful() || ! isset($this->extensions[$mime]) || strlen($res->body()) < 150) {
                    continue;
                }

                $path = 'products/' . Str::slug($brand) . '.' . $this->extensions[$mime];
                Storage::disk('public')->put($path, $res->body());
                $saved = $path;
                break;
            }

            if (! $saved) {
                $failed[] = $brand;
                $this->warn("Gagal : {$brand} ({$domain})");
                continue;
            }

            $count = Product::where('name', 'like', $brand . '%')->update(['image' => $saved]);
            $this->info("OK    : {$brand} -> {$saved} ({$count} produk)");
        }

        if ($failed) {
            $this->newLine();
            $this->warn('Brand gagal diunduh (tetap pakai gambar lama): ' . implode(', ', $failed));
            $this->line('Ganti manual lewat form edit produk di admin, atau taruh file di storage/app/public/products.');
        }

        return self::SUCCESS;
    }
}