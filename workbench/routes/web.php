<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd(\Illuminate\Support\Facades\Schema::getTables());

    return 'test';
});
