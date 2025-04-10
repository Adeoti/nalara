<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\News;

class BookmarkController extends Controller
{
    public function toggle(Request $request, News $news)
    {
        $user = $request->user();

        $bookmark = Bookmark::where('user_id', $user->id)
            ->where('news_id', $news->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['message' => 'Bookmark removed'], 200);
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'news_id' => $news->id
            ]);
            return response()->json(['message' => 'Bookmarked successfully'], 200);
        }
    }

    public function myBookmarks(Request $request)
    {
        $bookmarks = Bookmark::with('news')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->pluck('news');

        if ($bookmarks) {
            return response()->json($bookmarks);
        } else {
            return response()->json(['message' => 'No bookmarks found'], 404);
        }
    }
}
