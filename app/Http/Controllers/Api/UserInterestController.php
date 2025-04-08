<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserInterestController extends Controller
{
    //
    public function store(Request $request)
    {

        
        $validator = Validator::make($request->all(), [
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:news_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->user();

        $user->interests()->sync($request->category_ids); // Sync replaces old interests with new ones

        return response()->json([
            'message' => 'Interests saved successfully.',
            'interests' => $user->interests()->get()
        ], 200);
    }
}
