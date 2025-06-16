<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/reverse-me', function (Request $request) {
    $reversed = strrev($request->input('reverse_this'));
    return $reversed;
});
