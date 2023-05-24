<?php
namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Animer;
use App\Models\Categorie;
use App\Models\Intervenant;
use App\Models\Type;
use App\Models\Variable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ManagementController extends Controller
{

    public function categorieView()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }
        if (Categorie::all()->isEmpty()) {
            $listeCategorie = null;
        } else {
            $listeCategorie = Categorie::all();
        }
        return view('management.categories', ['categories' => $listeCategorie, 'urlEditCate' => url('/management/event/editCategorie/')]);
    }

    public function settingsView()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4 || Auth::user()->idPerm == 2) {
            redirect('/');
        }

        foreach(Variable::all() as $variable){
            switch ($variable->nomVariable) {
                case "etatWave":
                    $etatWave = $variable->dataVariable;
                    break;
                case "limiteFirstWave":
                    $limiteFirstWave = $variable->dataVariable;
                    break;
                case "sysMailEtat":
                    $sysMailEtat = $variable->dataVariable;
                    break;
            }
        }
        
        
        return view('management.settings', ["etatWave"=>$etatWave, "limiteFirstWave"=>$limiteFirstWave, "sysMailEtat"=>$sysMailEtat]);
    }

    public function intervenantView()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }

        if (Categorie::all()->isEmpty()) {
            $intervenants = null;
        } else {
        $intervenants = Intervenant::all();
        }
        return view('management.intervenants', ['intervenants' => $intervenants, 'urlIntervenant' => url('/management/event/editIntervenant/')]);
    }

    public function activiteView()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }
        if (Categorie::all()->isEmpty()) {
            $activites = null;
        } else {
        $activites = Activite::all();
        }
        $types = Type::all();
        $categories = Categorie::all();
        $intervenants = Intervenant::all();
        $anime = Animer::all();
        return view('management.activites', ['urlAPI' => url('/'), 'anime' => $anime, 'urlEditAct' => url('/management/event/editActivite/'), 'activites' => $activites, 'types' => $types, 'intervenants' => $intervenants, 'categories' => $categories]);
    }

    public function getAllAnimer()
    {
        /* Requête pour récuper les animateurs */
        $listeAnime = Animer::all();
        return response()->json($listeAnime);
    }
}
