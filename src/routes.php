<?php

Route::get(Config::get('laravel-compleet::url'), 'Etrepat\LaravelCompleet\CompleetController@index');
Route::get(Config::get('laravel-compleet::url') . '/search', 'Etrepat\LaravelCompleet\CompleetController@search');
