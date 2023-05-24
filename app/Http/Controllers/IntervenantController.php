<?php

namespace App\Http\Controllers;

use App\Models\Intervenant;
use Illuminate\Http\Request;
use App\Models\Activite;
use App\Models\Animer;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class IntervenantController extends Controller
{

    public function addIntervenant(Request $request)
    {
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }

        /* Validating the input. */
        $validator = Validator::make($request->all(), [
            'nomIntervenant' => ['required', 'string'],
            'prenomIntervenant' => ['required', 'string'],
            'professionIntervenant' => ['required', 'string'],
            'intExt' => ['required', 'boolean'],
        ]);


        

        /* Redirecting the user back to the register page with the errors and the input. */
        if ($validator->fails()) {
            return redirect("management/event/intervenant")
                ->withErrors($validator->errors())
                ->withInput();
        }

        $intervenant = new Intervenant();
        $intervenant->nomIntervenant = $request->nomIntervenant;
        $intervenant->prenomIntervenant = $request->prenomIntervenant;
        $intervenant->professionIntervenant = $request->professionIntervenant;
        $intervenant->isExt = $request->intExt;
        $intervenant->save();

        session()->flash('successIntervenant', "L'intervenant à bien été ajouté.");
        return redirect("management/event/intervenant");
    }

    public function editIntervenant(Request $request, $id)
    {
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }
        /* Validating the input. */
        $validator = Validator::make($request->all(), [
            'nomIntervenantEdit' => ['required', 'string'],
            'prenomIntervenantEdit' => ['required', 'string'],
            'professionIntervenantEdit' => ['required', 'string'],
            'intExtEdit' => ['required', 'boolean'],
        ]);


        /* Redirecting the user back to the register page with the errors and the input. */
        if ($validator->fails()) {
            session()->flash('alertIntervenant', "Erreur : Tous les champs n'ont pas été saisis.");
            return redirect("management/event/intervenant");
        }

        if (Intervenant::where('idIntervenant', $id)->exists()) {
            Intervenant::where('idIntervenant', $id)->update(['nomIntervenant' => $request->nomIntervenantEdit, 'prenomIntervenant' => $request->prenomIntervenantEdit, 'professionIntervenant' => $request->professionIntervenantEdit, 'isExt' => $request->intExtEdit, ]);
            session()->flash('successIntervenant', "L'intervenant à bien été éditée.");
        } else {
            session()->flash('alertIntervenant', "Une erreur s'est produite, l'intervenant n'existe pas.");
        }
        return redirect("management/event/intervenant");
    }

    public function deleteIntervenant($id)
    {
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }

        if (!Intervenant::where('idIntervenant', $id)->exists()) {
            session()->flash('alertIntervenant', "Une erreur s'est produite, l'intervenant n'existe pas.");
            return redirect("/management/event/intervenant");
        }
        if (!Animer::where('idIntervenant', $id)->exists()) {
            Intervenant::where('idIntervenant', $id)->delete();
            session()->flash('successIntervenant', "L'intervenant à bien été supprimée.");
        } else {
            session()->flash('alertIntervenant', "L'intervenant intervient dans une ou plusieurs activités.");
        }
        return redirect("management/event/intervenant");
    }
}
