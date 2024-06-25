<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// o prefix é para indicar a versão da api
Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::post         ('me',       [AuthController::class, 'me']              );
    Route::post         ('refresh',  [AuthController::class, 'refresh']         );
    Route::post         ('logout',   [AuthController::class, 'logout']          );
    Route::apiResource  ('cliente',   'App\Http\Controllers\ClienteController'  );
    Route::apiResource  ('carro',     'App\Http\Controllers\CarroController'    );
    Route::apiResource  ('locacao',   'App\Http\Controllers\LocacaoController'  );
    Route::apiResource  ('marca',     'App\Http\Controllers\MarcaController'    );
    Route::apiResource  ('modelo',    'App\Http\Controllers\ModeloController'   );
});


Route::post('login',    [AuthController::class, 'login']);





//Route::resource('cliente', 'App\Http\Controllers\ClienteController');

//     Route::post('me', 'App\Http\Controllers\AuthController@me');
//     Route::post('logout', 'App\Http\Controllers\AuthController@logout');
//     Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
//     Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
//     Route::apiResource('carro', 'App\Http\Controllers\CarroController');
//     Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
//     Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
//     Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');


// Route::post('login', 'App\Http\Controllers\AuthController@login');
