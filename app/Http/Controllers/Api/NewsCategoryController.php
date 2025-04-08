<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsCategoryController extends Controller
{
    //
    public function index()
{
    $categories = \App\Models\NewsCategory::select('id', 'name')->get();

    return response()->json($categories);
}

}
