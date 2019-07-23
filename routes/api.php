<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/setFilesLink', function () {
    Artisan::call('storage:link');
});

Route::get('/hola', function () {
    return "Mundo";
});

Route::middleware(["cors"])->group(function () { 
    Route::post("/login", "UsersController@login");
    Route::post('/users', 'UsersController@create');
    Route::get("/aus", "UsersController@activeUserSessions");
    Route::post("/files", "FilesController@create");

    Route::post('/details', 'DetailsController@create');
    Route::get('/details/{user_id}', 'DetailsController@retrieve');
    
    Route::get('/objectives/{id?}', 'ObjectivesController@retrieve');
    Route::get('/habits/{id?}', 'HabitsController@retrieve');
    Route::get('/levels/{id?}', 'LevelsController@retrieve');  
});

Route::middleware(["TokenChecker", "cors"])->group(function () {

    //Administración de chats
    Route::get("/chats/contacts", "ChatsController@getContacts");
    Route::post("/chats", "ChatsController@addChat");
    Route::get("/chats/{chat_id?}", "ChatsController@getChats");

    Route::post("/chats/{chat_id}/messages", "ChatsController@addMessage");
    Route::get("/chats/{chat_id}/messages/{message_id?}", "ChatsController@getMessages");

    Route::get("/files/{id}", "FilesController@retrieve");
    Route::delete("/files/{id}", "FilesController@delete");   

    Route::get('/users/{id?}', 'UsersController@retrieve');
    Route::post('/users/{id}', 'UsersController@update');
    Route::delete('/users/{id}', 'UsersController@delete');   
    Route::post("/logout", "UsersController@logout");

    Route::post('/roles', 'RolesController@create');
    Route::get('/roles/{id?}', 'RolesController@retrieve');
    Route::post('/roles/{id}', 'RolesController@update');
    Route::delete('/roles/{id}', 'RolesController@delete');

    Route::post('/pools', 'PoolsController@create');
    Route::get('/pools/{id?}', 'PoolsController@retrieve');
    Route::post('/pools/{id}', 'PoolsController@update');
    Route::delete('/pools/{id}', 'PoolsController@delete');



    Route::post('/levels', 'LevelsController@create');
    Route::post('/levels/{id}', 'LevelsController@update');
    Route::delete('/levels/{id}', 'LevelsController@delete');

    Route::post('/objectives', 'ObjectivesController@create');
    Route::post('/objectives/{id}', 'ObjectivesController@update');
    Route::delete('/objectives/{id}', 'ObjectivesController@delete');

    Route::post('/habits', 'HabitsController@create');
    Route::post('/habits/{id}', 'HabitsController@update');
    Route::delete('/habits/{id}', 'HabitsController@delete');
});