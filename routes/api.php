<?php

use App\Http\Controllers\AuthController;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\ManagementController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



/* Une route qui sera utilisée pour obtenir la liste de toutes les activités. */
Route::get('/activite', [ActiviteController::class, "listeGlobalActivite"]);

/* Une route qui sera utilisée pour obtenir la liste de toutes les inscription d'un élève. */
Route::get('/inscrit/{idUsers}', [InscriptionController::class, "listeInscriptionEleve"]);

Route::get('/insc/{idUsers}', [ActiviteController::class, "listeActiviteEleve"]);

Route::get('/activite2', [ActiviteController::class, "listeGlobalActiviteForUser"]);

route::get('/categorie',[CategorieController::class, "listeGlobalCategorie"]);

route::get('/type',[TypeController::class, "listeGlobalType"]);

Route::get('/classe/getAll', function(){
    return response()->json(Classe::all());
})->name("GetClasse");

Route::get('/eleves/getUnregisterByClass/{idClasse}',  [AuthController::class, 'getUnregisterEleves'])->name("GetUnregisterByClass");

Route::get('/eleves/getRegisterByClass/{idClasse}',  [AuthController::class, 'getRegisterEleves'])->name("GetRegisterByClass");

Route::get('/anime/getAll', [ManagementController::class, "getAllAnimer"])->name("getAllAnimer");