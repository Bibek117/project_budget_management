<?php

use App\Http\Controllers\ContacttypeController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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







Route::controller(RecordController::class)->group(function(){
    Route::post('/create-record', 'createRecord');
});


