<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PerfisController;
use App\Http\Controllers\ServicosController;
use App\Http\Controllers\AgendamentosController;

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
Route::middleware('auth:api')->get('/logout', [AuthController::class, 'logout']);

Route::get('agendamentos/agendamentoListarHome', [AgendamentosController::class, 'agendamentoListarPaginaHomeFront']);
Route::get('agendamentos/agendamentoFiltrarHome', [AgendamentosController::class, 'agendamentoFiltrarPaginaHomeFront']);

Route::get('servicos/servicoListar', [ServicosController::class, 'servicoListar']);

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

    // Rotas de perfis
    Route::prefix('perfis')->group(function () {
        Route::post('/perfilCadastrar', [PerfisController::class, 'perfilCadastrar']);
        Route::get('/perfilConsultar/{id}', [PerfisController::class, 'perfilConsultar']);
        Route::get('/perfilListar', [PerfisController::class, 'perfilListar']);
        Route::patch('/perfilEditar/{id}', [PerfisController::class, 'perfilEditar']);
        Route::post('/perfilFiltrar', [PerfisController::class, 'perfilFiltrar']);
        Route::delete('/perfilDeletar/{id}', [PerfisController::class, 'perfilDeletar']);
    });

    // Rotas de Servicos
    Route::prefix('servicos')->group(function () {
        Route::post('/servicoCadastrar', [ServicosController::class, 'servicoCadastrar']);
        Route::get('/servicoConsultar/{id}', [ServicosController::class, 'servicoConsultar']);

        Route::patch('/servicoEditar/{id}', [ServicosController::class, 'servicoEditar']);
        Route::post('/servicoFiltrar', [ServicosController::class, 'servicoFiltrar']);
        Route::delete('/servicoDeletar/{id}', [ServicosController::class, 'servicoDeletar']);
    });

    // Rotas de agendamentos
    Route::prefix('agendamentos')->group(function () {
        Route::post('/agendamentoCadastrar', [AgendamentosController::class, 'agendamentoCadastrar']);
        Route::get('/agendamentoConsultar/{id}', [AgendamentosController::class, 'agendamentoConsultar']);
        Route::get('/agendamentoListar', [AgendamentosController::class, 'agendamentoListar']);
        Route::patch('/agendamentoEditar/{id}', [AgendamentosController::class, 'agendamentoEditar']);
        Route::post('/agendamentoFiltrar', [AgendamentosController::class, 'agendamentoFiltrar']);
        Route::delete('/agendamentoDeletar/{id}', [AgendamentosController::class, 'agendamentoDeletar']);
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
