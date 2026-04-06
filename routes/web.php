<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.dashboard.index');
});
Route::get("/" ,function() {
    return view ('pages.dashboard.index');
});

