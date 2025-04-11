<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\News;
use App\Models\NewsSource;
use Illuminate\Support\Str;
use App\Models\NewsCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchNewsFromSources implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $sources = NewsSource::where('is_active', true)->get();

        foreach ($sources as $source) {
            match ($source->type) {
                'wordpress' => $this->handleWordPress($source),
                // 'rss' => $this->handleRss($source),
                // 'custom' => $this->handleCustom($source),
            };
        }
    }

    private function handleWordPress(NewsSource $source)
    {
        // $url = rtrim($source->base_url, '/') . '/wp-json/wp/v2/posts?per_page=10';
        $url = rtrim($source->base_url, '/') . '/wp-json/wp/v2/posts?per_page=10&_embed';
        Log::info("Fetching from {$source->name}: {$url}");


        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'application/json',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Referer' => $source->base_url, // Or you can set the referer to the base URL if needed
            ])->get($url);


            // $response = Http::get($url);

            Log::info("Status: " . $response->status());
            Log::info("Response: " . $response->body());


            if ($response->successful()) {
                foreach ($response->json() as $item) {
                    $this->storeNews($item, $source);
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed fetching WordPress news from {$source->name}: {$e->getMessage()}");
        }
    }
    private function storeNews(array $item, NewsSource $source)
    {
        $slug = $item['slug'];
        if (News::where('slug', $slug)->exists()) return;

        // Extract the first category (if any)
        $categoryId = null;
        if (!empty($item['categories'][0])) {
            $wpCategoryId = $item['categories'][0]; // WordPress category ID

            // Look for categories inside _embedded['wp:term']
            $wpTerms = $item['_embedded']['wp:term'] ?? [];

            foreach ($wpTerms as $taxonomy) {
                foreach ($taxonomy as $term) {
                    if ($term['taxonomy'] === 'category') {
                        $cleanName = ucwords(strtolower(trim(html_entity_decode($term['name']))));

                        $category = NewsCategory::firstOrCreate(
                            ['name' => $cleanName],
                            ['slug' => Str::slug($cleanName)]
                        );

                        $categoryId = $category->id;
                        break 2;
                    }
                }
            }
        }



        News::create([
            'news_source_id' => $source->id,
            'news_category_id' => $categoryId,
            // 'title' => $item['title']['rendered'],
            'title' => html_entity_decode($item['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'slug' => $slug,
            'summary' => strip_tags($item['excerpt']['rendered']),
            'content' => $item['content']['rendered'],
            'image' => $item['_embedded']['wp:featuredmedia'][0]['source_url']
                ?? $item['yoast_head_json']['og_image'][0]['url']
                ?? null,

            'author' => $item['_embedded']['author'][0]['name'] ?? $source->name,
            'url' => $item['link'],
            'published_at' => Carbon::parse($item['date']),
        ]);
    }

    // private function storeNews_working(array $item, NewsSource $source)
    // {
    //     $slug = $item['slug'];
    //     if (News::where('slug', $slug)->exists()) return;

    //     News::create([
    //         'news_source_id' => $source->id,
    //         'title' => $item['title']['rendered'],
    //         'slug' => $slug,
    //         'summary' => strip_tags($item['excerpt']['rendered']),
    //         'content' => $item['content']['rendered'],
    //         'image' => $item['yoast_head_json']['og_image'][0]['url'] ?? null, // optional
    //         'author' => $item['_embedded']['author'][0]['name'] ?? $source->name,
    //         'url' => $item['link'],
    //         'published_at' => Carbon::parse($item['date']),
    //     ]);
    // }

}
