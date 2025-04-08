<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ReactionController extends Controller
{
    //
    public function react(Request $request)
    {
       

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:like,love,haha,wow,sad,angry,happy',
            'reactable_type' => 'required|string|in:news,comment',
            'reactable_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->user();

        $modelMap = [
            'news' => \App\Models\News::class,
            'comment' => \App\Models\Comment::class,
        ];

        $modelClass = $modelMap[$request->reactable_type] ?? null;

        if (! $modelClass || ! $modelClass::find($request->reactable_id)) {
            return response()->json(['error' => 'Reactable item not found'], 404);
        }

        $reactable = $modelClass::findOrFail($request->reactable_id);

        $reaction = $reactable->reactions()->updateOrCreate(
            ['user_id' => $user->id],
            ['type' => $request->type]
        );

        return response()->json([
            'message' => 'Reaction recorded successfully',
            'reaction' => $reaction
        ]);
    }



    public function getReactions(Request $request, $type, $id)
    {
        $modelMap = [
            'news' => \App\Models\News::class,
            'comment' => \App\Models\Comment::class,
        ];

        $modelClass = $modelMap[$type] ?? null;

        if (!$modelClass || ! $modelClass::find($id)) {
            return response()->json(['error' => 'Reactable item not found'], 404);
        }

        $reactable = $modelClass::findOrFail($id);

        // Group reactions by type and count them
        $reactions = $reactable->reactions()
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        // If authenticated, include current user's reaction
        $userReaction = null;
        if ($request->user()) {
            $userReaction = $reactable->reactions()
                ->where('user_id', $request->user()->id)
                ->first();
        }

        return response()->json([
            'reactable_type' => $type,
            'reactable_id' => $id,
            'reactions' => $reactions,
            'user_reaction' => $userReaction?->type,
        ]);
    }
}
