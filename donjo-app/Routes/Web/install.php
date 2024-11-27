<?php

Route::group('install', static function (): void {
    Route::match(['GET', 'POST'], '/', 'Install@index');
    Route::match(['GET', 'POST'], '/index', 'Install@index');
    Route::match(['GET', 'POST'], '/server', 'Install@server');
    Route::match(['GET', 'POST'], '/folders', 'Install@folders');
    Route::match(['GET', 'POST'], '/database', 'Install@database');
    Route::match(['GET', 'POST'], '/migrations', 'Install@migrations');
    Route::match(['GET', 'POST'], '/user', 'Install@user');
    Route::match(['GET', 'POST'], '/finish', 'Install@finish');
    Route::match(['GET', 'POST'], '/syarat_sandi/{password?}', 'Install@syarat_sandi');
});