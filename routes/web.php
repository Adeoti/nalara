<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/fetch', function () {
    dispatch(new \App\Jobs\FetchNewsFromSources());
});
