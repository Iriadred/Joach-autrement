<?php

use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\IntervenantController;
use App\Http\Controllers\ManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    /* Checking if the user is logged in. If not, it redirects to the login page. */
    if (!Auth::check()) {
        return redirect('/login');
    }
    if(Auth::user()->idPerm == 4){
        return redirect('/liste-activite');
    }
    return view('publicActiviteListe', ['urlAPI' => url('/')]);
})->name('home');


/****  The above code is defining routes for the management pages. */

Route::get('/management/event', [ManagementController::class, 'mangementView'])->middleware('auth')->name('event');

/**** Les categorie */
Route::get('/management/event/categories', [ManagementController::class, 'categorieView'])->middleware('auth')->name('allCategories');
Route::post('/management/event/addCategorie', [CategorieController::class, 'addCategorie'])->middleware('auth')->name('addCategorie');
Route::post('/management/event/editCategorie/{id}', [CategorieController::class, 'editCategorie'])->middleware('auth')->name('editCategorie');
Route::get('/management/event/deleteCategorie/{id}', [CategorieController::class, 'deleteCategorie'])->middleware('auth')->name('deleteCategorie');


/**** Les intervenants */

Route::get('/management/event/intervenant', [ManagementController::class, 'intervenantView'])->middleware('auth')->name('allIntervenant');
Route::post('/management/event/addIntervenant', [IntervenantController::class, 'addIntervenant'])->middleware('auth')->name('addIntervenant');
Route::post('/management/event/editIntervenant/{id}', [IntervenantController::class, 'editIntervenant'])->middleware('auth')->name('editIntervenant');
Route::get('/management/event/deleteIntervenant/{id}', [IntervenantController::class, 'deleteIntervenant'])->middleware('auth')->name('deleteIntervenant');

/**** Les activités */

Route::get('/management/event/activite', [ManagementController::class, 'activiteView'])->middleware('auth')->name('allActivite');
Route::post('/management/event/addActivite', [ActiviteController::class, 'addActivite'])->middleware('auth')->name('addActivite');
Route::post('/management/event/editActivite/{id}/{type}', [ActiviteController::class, 'editActivite'])->middleware('auth')->name('editActivite');
Route::get('/management/event/deleteActivite/{type}/{id}', [ActiviteController::class, 'deleteActivite'])->middleware('auth')->name('deleteActivite');

/* Settings */
Route::get('/settings/', [ManagementController::class, 'settingsView'])->middleware('auth')->name('settingsView');


Route::get('/account/eleves', [AuthController::class, 'viewAddAccountEleves'])->middleware('auth')->name("ViewCreateEAccount");
Route::post('/account/csvAdd', [AuthController::class, 'addEleveWithCSV'])->middleware('auth')->name("addCSV");


/* Calling the `RedirectListeActivite` method in the `ActiviteController` class. */
Route::get('/liste-activite', [ActiviteController::class, 'RedirectListeActivite'])->name("eleve");

/* A route that is calling the `InscriptionActivite` method in the `InscriptionController` class. */
Route::get('/Inscription/{idUsers}/{codeType}/{idActivite}', [InscriptionController::class, 'InscriptionActivite']);

/* A route that is calling the `DesinscriptionActivite` method in the `InscriptionController` class. */
Route::get('/Desinscription/{idUsers}/{codeType}/{idActivite}', [InscriptionController::class, 'DesinscriptionActivite']);

/* A route that is calling the `RedirectlisteActiviteParEleve` method in the `ActiviteController`
class. */
Route::get('/mes-activite', [ActiviteController::class, 'RedirectlisteActiviteParEleve'])->name("mesActivite");

Route::get('/DesinscriptionParEleve/{idUsers}/{codeType}/{idActivite}', [InscriptionController::class, 'DesinscriptionActivitePourListeActiviteParEleve']);

/****  The above code is defining routes for the Authantification pages. */

/* A route for the register page. */
Route::get('/register', [AuthController::class, 'registerView'])->name('registerView');
Route::post('/post/verifRegister', [UserController::class, 'VerfiRegister'])->name("VerfiRegister");
Route::get('/verifMail/{token}', [UserController::class, 'TokenRegisterEleve'])->name("TokenRegisterEleve");

/* A route for the login page. */
Route::get('/login', [AuthController::class, 'loginView'])->name('loginView');
Route::post('/profile', [AuthController::class, 'loginUser'])->name('loginUser');

/* A route for resetting the password. */
Route::get('/forget-password', [UserController::class, 'resetPassView'])->name('lostPassword');
Route::post('/reset-password', [UserController::class, 'resetPasswordSendMail'])->name('resetPassword');
Route::get('/new-password/{token}', [UserController::class, 'newPassword'])->name('resetPasswordView');
Route::post('/set-new-password', [UserController::class, 'setNewPassword'])->name('setNewPassword');


/* A route for the logout page. */
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
