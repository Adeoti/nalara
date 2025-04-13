<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CentralAppManagerController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/fetch', function () {
    dispatch(new \App\Jobs\FetchNewsFromSources());
});



Route::get('/', [CentralAppManagerController::class, 'index'])->name('central.apps.index');
