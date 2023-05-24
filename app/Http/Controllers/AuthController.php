<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;

class AuthController extends Controller
{

    /**
     * It returns the view called "login"
     */
    public function loginView()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view("auth.login", ['urlAPI' => url('/')]);
    }

    /**
     * It returns the view "register"
     */
    public function registerView()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view("auth.register", ['urlAPI' => url('/')]);
    }

    public function getUnregisterEleves($idClasse) // API 
    {
        /* Requête pour récuper les utilisateurs avec comme permission 4 (élèves) qui ont première inscripiton à 1 */
        $listeEleve = User::where('premiereConnexion', true)->where('idClasse', $idClasse)->where('idPerm', 4)->select('idUsers', 'nomUsers')->get();
        return response()->json($listeEleve);
    }

    public function getRegisterEleves($idClasse) // API 
    {
        if ($idClasse == 'personnel') {
            $listeEleve = User::where('premiereConnexion', false)->whereNotIn('idPerm', [4])->select('idUsers', 'nomUsers', 'validationMail')->get();
        } else {
            $listeEleve = User::where('premiereConnexion', false)->where('idClasse', $idClasse)->where('idPerm', 4)->select('idUsers', 'nomUsers', 'validationMail')->get();
        }


        /* Requête pour récuper les utilisateurs avec comme permission 4 (élèves) qui ont première inscripiton à 0 */

        return response()->json($listeEleve);
    }


    public function viewAddAccountEleves()
    {
        if (Auth::check()) {
            if (Auth::user()->idPerm == 2 || Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
                redirect('/');
            }
            $countUser = User::Where("idPerm", '4')->count();
            $countClass = Classe::count();
            return view("management.accountEleve", ['countEleves' => $countUser, 'countClasse' => $countClass]);
        }
    }

    public function addEleveWithCSV(Request $request)
    {
        
    }


    public function loginUser(Request $request)
    {
        /* Validating the input. */
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'integer'],
            /* A validation rule for password. */
            'password' => ['required']
        ]);

        /* Redirecting the user back to the register page with the errors and the input. */
        if ($validator->fails()) {
            return redirect('/login')
                ->withErrors($validator->errors())
                ->withInput();
        }

        Auth::attempt(['idUsers' => $request->name, 'password' => $request->password, 'validationMail' => 1]);

        if (Auth::check()) {
            return redirect('/');
        } else {
            session()->flash('alert', 'Mot de passe incorrect');
            return redirect('/login');
        }
    }

    public function logout()
    {

        Auth::logout();
        return redirect('/login');
    }
}
