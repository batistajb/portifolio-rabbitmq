<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix'=> 'posts'], function() {
    #CRUD posts
    Route::get('',[PostController::class, 'index']);
    Route::post('',[PostController::class, 'store']);
    Route::get('{uuid}',[PostController::class, 'getPost']);
    Route::put('{post}',[PostController::class, 'update']);
    Route::delete('{post}',[PostController::class, 'delete']);

    # list posts of profiles following and followers, list posts by userUuid
    Route::get('follows/{user}',[PostController::class, 'follows']);
    Route::get('followers/{user}',[PostController::class, 'followers']);
    Route::get('my/{user}',[PostController::class, 'myPosts']);

    #manage like and deslike
    Route::post('like/{post_id}',[PostController::class, 'likePost']);
    Route::post('deslike/{post}',[PostController::class, 'deslikePost']);
});

Route::group(['prefix'=> 'profile'], function() {
    Route::get('',[UserController::class, 'getAll']);
    Route::get('{uuid}',[UserController::class, 'find']);
});

Route::get('follows/{user}',[FollowController::class, 'follows']);
Route::get('followers/{user}',[FollowController::class, 'followers']);
Route::post('follow/{user}',[FollowController::class, 'follow']);
Route::post('unfollow/{user}',[FollowController::class, 'unfollow']);
