<?php

Route::get('/tasks', [
    'as' => 'tasks.index',
    'uses' => 'TasksController@index'
]);

Route::post('/tasks/export', [
    'as' => 'tasks.export',
    'uses' => 'TasksController@export',
]);

Route::post('/tasks/{id}/finish', [
    'as' => 'tasks.finish',
    'uses' => 'TasksController@finish',
]);
Route::post('/tasks/{id}/long-running', [
    'as' => 'tasks.is_long_running',
    'uses' => 'TasksController@longRunning'
]);

Route::get('/tasks/{id}', [
    'as' => 'tasks.show',
    'uses' => 'TasksController@show'
]);

Route::get('/tasks/{id}/log', [
    'as' => 'tasks.log',
    'uses' => 'TasksController@log'
]);

Route::get('/tasks/ping-school/{id}', [
    'as' => 'tasks.ping',
    'uses' => 'TasksController@ping'
]);
