<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class FixNewsTitles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-news-titles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newsItems = News::all();
    
        foreach ($newsItems as $news) {
            $cleanTitle = html_entity_decode($news->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
            // Only update if different
            if ($cleanTitle !== $news->title) {
                $news->title = $cleanTitle;
                $news->save();
            }
        }
    
        $this->info('Titles fixed successfully!');
    }
}
