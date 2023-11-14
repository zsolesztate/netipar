<?php

use App\Http\Controllers\ContactsController;
use App\Http\Controllers\ContactsPhonenumbersController;
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


Route::resource('contacts', ContactsController::class);
//Route::resource('contacts/{id}/phones', ContactsController::class, ['parameters' => ['phones' => 'contact_id']]);
Route::delete('/contacts/{id}', [ContactsController::class, 'destroy']);
Route::post('/contacts', [ContactsController::class, 'store']);
Route::post('/editcontact', [ContactsController::class, 'edit']);