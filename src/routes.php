<?php

Route::post('/tasks/export', [
    'as' => 'tasks.export',
    'uses' => 'Label305\\Tasks\\Http\\Controllers\\TasksController@export',
]);

Route::post('/tasks/{id}/finish', [
    'as' => 'tasks.finish',
    'uses' => 'Label305\\Tasks\\Http\\Controllers\\TasksController@finish',
]);
Route::post('/tasks/{id}/long-running', [
    'as' => 'tasks.is_long_running',
    'uses' => 'Label305\\Tasks\\Http\\Controllers\\TasksController@longRunning'
]);

Route::get('/tasks/{id}/log', [
    'as' => 'tasks.log',
    'uses' => 'Label305\\Tasks\\Http\\Controllers\\TasksController@log'
]);
