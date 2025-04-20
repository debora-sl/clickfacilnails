<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

// Rotas não autenticadas para cadastrar, logar e logout
Route::post('users/userCadastrar', [UserController::class, 'userCadastrar']);
Route::post('userLogin', [UserController::class, 'userLogar']);

// Rotas (usuários que estejam autenticados)
Route::middleware('auth:api')->group(function () {

    // Rotas de users
    Route::prefix('users')->group(function () {
        Route::get('/userConsultar/{id}', [UserController::class, 'userConsultar']);
        Route::get('/userListar', [UserController::class, 'userListar']);
        Route::patch('/userEditar/{id}', [UserController::class, 'userEditar']);
        Route::post('/userFiltrar', [UserController::class, 'userFiltrar']);
        Route::delete('/userDeletar/{id}', [UserController::class, 'userDeletar']);
    });
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
