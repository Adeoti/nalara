<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use App\Models\Comment;
use App\Models\NewsView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{

    //
    // public function index()
    // {
    //     $news = News::all();
    //     if ($news->isEmpty()) {
    //         return response()->json(['message' => 'No news found'], 404);
    //     }
    //     return response()->json($news);
    // }


    // public function index()
    // {
    //     $news = News::with('source')->with('newsCategory')->limit(2)->orderBy('id', 'desc')->get();

    //     if ($news->isEmpty()) {
    //         return response()->json(['message' => 'No news found'], 404);
    //     }

    //     // If user is authenticated, include is_bookmarked flag
    //     $user = auth()->user();

    //     $news = $news->map(function ($item) use ($user) {
    //         $item->is_bookmarked = false;

    //         if ($user) {
    //             $item->is_bookmarked = $item->bookmarks()->where('user_id', $user->id)->exists();
    //         }

    //         // Optionally hide the news_source_id and only return the full source
    //         unset($item->news_source_id);

    //         return $item;
    //     });

    //     return response()->json($news);
    // }
    public function index()
    {
        $news = News::with(['source', 'newsCategory', 'comments', 'reactions'])
                    ->orderBy('id', 'desc')
                    ->get();
    
        if ($news->isEmpty()) {
            return response()->json(['message' => 'No news found'], 404);
        }
    
        $user = auth()->user();
    
        $news = $news->map(function ($item) use ($user) {
            $item->is_bookmarked = false;
    
            if ($user) {
                $item->is_bookmarked = $item->bookmarks()->where('user_id', $user->id)->exists();
            }
    
            // Hide foreign key
            unset($item->news_source_id);
    
            return $item;
        });
    
        return response()->json($news);
    }
    

    // GET /api/news/from-followed-sources
    public function newsFromFollowedSources(Request $request)
    {
        $user = $request->user();

        $sourceIds = $user->followedSources()->pluck('news_sources.id');

        $news = News::whereIn('news_source_id', $sourceIds)
            ->latest()
            ->paginate(20);

        return response()->json($news);
    }


    public function show(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $alreadyViewed = false;

        // Check if the user is authenticated
        if ($request->user()) {
            // Check if the logged-in user has already viewed this news
            $alreadyViewed = \App\Models\NewsView::where('news_id', $news->id)
                ->where('user_id', $request->user()->id)
                ->exists();

            if (!$alreadyViewed) {
                // Create a new view record for the authenticated user
                NewsView::create([
                    'news_id' => $news->id,
                    'user_id' => $request->user()->id,
                    'ip_address' => $request->ip(),
                ]);
            }
        } else {
            // Track views for guest users using their IP address
            $alreadyViewed = \App\Models\NewsView::where('news_id', $news->id)
                ->where('ip_address', $request->ip())
                ->exists();

            if (!$alreadyViewed) {
                // Create a new view record for the guest user using their IP
                NewsView::create([
                    'news_id' => $news->id,
                    'user_id' => null, // No user ID for guests
                    'ip_address' => $request->ip(),
                ]);
            }
        }

        // Return the news details and the view count (total unique readers)
        return response()->json([
            'news' => $news,
            'readers_count' => NewsView::where('news_id', $news->id)->count(), // Count the unique views
        ]);
    }


    public function addComment(Request $request)
    {
        // $request->validate([
        //     'news_id' => 'required|exists:news,id',
        //     'comment' => 'required|string',
        //     'parent_id' => 'nullable|exists:comments,id', // Optional reply to another comment
        // ]);

        $validator = Validator::make($request->all(), [
            'news_id' => 'required|exists:news,id',
            'comment' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'news_id' => $request->news_id,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id, // Could be null or another comment's ID
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
            'data' => $comment->load('user'),
        ]);
    }

    public function addCommenti(Request $request, News $news)
    {

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create the comment
        $comment = $news->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Comment added successfully!',
            'comment' => $comment,
        ], 200);
    }


    public function getComments($newsId)
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('news_id', $newsId)
            // ->whereNull('parent_id') // Only top-level comments
            ->latest()
            ->get();

        return response()->json([
            'data' => $comments
        ]);
    }
}
