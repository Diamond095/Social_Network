<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetController;
use App\Http\Controllers\PostImageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use PHPUnit\Framework\Attributes\PostCondition;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=>'auth:sanctum'], function(){
    Route::post('/post_image', PostImageController::class);

    Route::post('/post',[PostController::class, 'store']);
    Route::get('/posts',[PostController::class, 'getPosts']);
    Route::post('/post/{post}/toggle_like', [PostController::class, 'toggleLike']);
    Route::post('/post/{post}/repost', [PostController::class, 'repost']);
    Route::post('/post/{post}/comment', [PostController::class, 'comment']);
    Route::get('/post/{post}/comments', [PostController::class, 'getComments']);
    
    Route::get('/feed', [UserController::class, 'feed']);
    Route::post('/user/{user}/toggle_following',[UserController::class, 'toggleFollowing']);
    Route::get('/users',[UserController::class, 'getUsers']);
    Route::get('/user/{user}/posts',[UserController::class, 'showPostOfUser']);
    Route::post('/user/stats',[UserController::class, 'stat']);
    Route::post('/user/stats/subscribers',[UserController::class, 'getSubscribers']);
    Route::get('/user/id', [UserController::class, 'getUserId']);
    Route::post('/user/stats/followings',[UserController::class, 'getFollowings']);
});

