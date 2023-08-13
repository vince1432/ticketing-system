<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketPriorityController;
use App\Http\Controllers\TicketStatusController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::get('')

Route::post('/login', [AuthController::class, 'login']);
Route::post('/ticket', [TicketController::class, 'store']);
// TODO:
// gates
// token timeout
// token abilities
// refresh token
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/user', UserController::class);
    Route::prefix('/ticket/comment')->group(function () {
        Route::get('/', [TicketController::class, 'comments']);
        Route::get('/{ticket}', [TicketController::class, 'comment']);
        Route::patch('/{ticket}', [TicketController::class, 'updateComment']);
        Route::delete('/{ticket}', [TicketController::class, 'removeComment']);
        Route::post('/', [TicketController::class, 'addComment']);
    });
    Route::patch('/ticket/close/{ticket}', [TicketController::class, 'close']);
    Route::apiResource('/ticket', TicketController::class)->except('store', 'destroy');

    Route::get('/ticket-priority', [TicketPriorityController::class, 'index']);
    Route::apiResource('/ticket-status', TicketStatusController::class);
});
// Route::post('/tokens/create', function (Request $request) {
//     $token = $request->user()->createToken($request->token_name);

//     return ['token' => $token->plainTextToken];
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
