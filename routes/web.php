<?php

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return (new BaseController)->sendSuccessJson(
        ['timestamp' => now(),],
        'Task api running...'
    );
});

Route::fallback(function () {
    return (new BaseController)->sendErrorJson('Not Found!');
});