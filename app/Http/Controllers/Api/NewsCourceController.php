<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use App\Models\User;
use App\Models\NewsSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NewsCourceController extends Controller
{



    //

    public function toggleFollow(NewsSource $source)
    {
        $user = auth()->user();

        if ($user->followedSources()->where('news_source_id', $source->id)->exists()) {
            $user->followedSources()->detach($source);
            return response()->json(['status' => 'unfollowed']);
        } else {
            $user->followedSources()->attach($source);
            return response()->json(['status' => 'followed']);
        }
    }

    public function allSources()
    {
        $user = auth()->user();

        $sources = NewsSource::where('is_active', true)
            ->has('news')
            ->get()
            ->map(function ($source) use ($user) {
                return [
                    'id' => $source->id,
                    'name' => $source->name,
                    'base_url' => $source->base_url,
                    'type' => $source->type,
                    'logo' => $source->logo ?? null,
                    'is_following' => $user->followedSources->contains($source->id),
                ];
            });

        return response()->json($sources);
    }


    // GET /api/news-sources
    public function allSourcesi(Request $request)
    {
        $user = $request->user();

        $followed = $user->followedSources()->pluck('news_sources.id')->toArray();

        $sources = NewsSource::select('id', 'name', 'logo')
            ->get()
            ->map(function ($source) use ($followed) {
                return [
                    'id' => $source->id,
                    'name' => $source->name,
                    'logo' => $source->logo,
                    'is_followed' => in_array($source->id, $followed),
                ];
            });

        return response()->json($sources);
    }


    // GET /api/news-source/{id}
    public function newsBySource(NewsSource $source)
    {
        $news = $source->news()->latest()->paginate(20);

        // $news = News::where('news_source_id', $source->id)->latest()->paginate(20);
        Log::info($source);
        Log::info($news);
        return response()->json([
            'source' => $source,
            'news' => $news
        ]);
    }



    // GET /api/followed-sources
    public function followedSources(Request $request)
    {
        // $user = $request->user();
        return response()->json(auth()->user()->followedSources);
    }
}
