<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CentralAppManager;

class CentralAppManagerController extends Controller
{
    //
    public function index()
{
    $apps = CentralAppManager::where('is_active', true)->get()->groupBy('group');

    return view('central_apps.index', compact('apps'));
}
    
}
