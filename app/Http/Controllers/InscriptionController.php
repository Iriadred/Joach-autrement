<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Inscrire;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InscriptionController extends Controller
{
    // Cette fonction est utilisée pour inscrire un élève à une activité


    function InscriptionActivite($idUsers, $codeType, $idActivite)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        if (Auth::user()->idPerm != 4) {
            return redirect('/');
        }

        /* Checking if the activity exists. */
        if (!Activite::where('codeType', $codeType)->where('idActivite', $idActivite)->exists()) {
            session()->flash('alertActivite', "Erreur : L'activité n'éxiste pas.");
            return redirect('/liste-activite');
        }

        /* Checking if the activity is cancelled. */
        if (Activite::where('idActivite', $idActivite)->where('codeType', $codeType)->where("activiteAnnule", 1)->exists()) {
            session()->flash('alertActivite', "Une erreur s'est produite, l'activité est annulé.");
            return redirect("/liste-activite");
        }

        /* Checking if the user is already registered for the activity. */
        if (Inscrire::where('codeType', $codeType)->where('idActivite', $idActivite)->where('idUsers', $idUsers)->exists()) {
            session()->flash('alertActivite', "Erreur : Vous êtes déjà inscrit.");
            return redirect('/liste-activite');
        }

        /* Getting the number of places available for an activity. */
        $nbPlaces = Activite::leftJoin('inscrire', function ($join) {
            $join->on('activite.codeType', '=', 'inscrire.codeType')
                ->where(DB::raw('activite.idActivite'), '=', DB::raw('inscrire.idActivite'));
        })->select(DB::raw('(activite.nbPlaceActivite - count(inscrire.idUsers)) as placeRestante '))->where("activite.codeType", $codeType)->where("activite.idActivite", $idActivite)->groupBy('activite.codeType', 'activite.idActivite', 'activite.idCategorie', 'activite.momentActivite', 'activite.dateActivite', 'activite.heureDebutActivite', 'activite.heureFinActivite', 'activite.titreActivite', 'activite.nbPlaceActivite', 'activite.descriptionActivite', 'activite.enteteActivite', 'activite.publicActivite', 'activite.lieu',)->get();
        foreach ($nbPlaces as $nbPlace) {
        }

        /* Getting the activity that the user is trying to register for. */
        $thisActivites = Activite::where('codeType', $codeType)->where('idActivite', $idActivite)->get();
        foreach ($thisActivites as $thisActivite) {
        }

        /* Getting all the activities that the user is registered for. */
        $listeActiv = Activite::with('type')->with('categorie')
            ->join('inscrire', function ($join) use ($idUsers) {
                $join->on('activite.codeType', '=', 'inscrire.codeType')
                    ->on('activite.idActivite', '=', 'inscrire.idActivite')
                    ->where('inscrire.idUsers', '=', $idUsers);
            })
            ->select(
                'activite.codeType',
                'activite.idActivite',
                'activite.heureDebutActivite',
                'activite.heureFinActivite',
            )
            ->groupBy('activite.codeType', 'activite.idActivite', 'activite.idCategorie', 'activite.momentActivite', 'activite.dateActivite', 'activite.heureDebutActivite', 'activite.heureFinActivite', 'activite.titreActivite', 'activite.nbPlaceActivite', 'activite.descriptionActivite', 'activite.enteteActivite', 'activite.publicActivite', 'activite.lieu')
            ->get();


        /* Checking if the user is already registered for an activity that is happening at the same
        time as the activity that the user is trying to register for. */
        $boolVerif = true;
        foreach ($listeActiv as $activite) {
            if (!(($thisActivite->heureFinActivite <= $activite->heureDebutActivite) || ($activite->heureFinActivite <= $thisActivite->heureDebutActivite))) {
                $boolVerif = false;
            }
        }

        /* Checking if the user is already registered for an activity that is happening at the same
        time as the activity that the user is trying to register for. */
        if (!$boolVerif) {
            session()->flash('alertActivite', "Erreur : Vous avez une ou plusieurs activité(s) en même temps.");
            return redirect('/liste-activite');
        }


        /* Checking if the user is already registered for an activity. */
        if ($nbPlace->placeRestante > 0) {
            $idUsers = Auth::user()->idUsers;
            /* Checking if the user is a student and if the user is registered for less than 3
            activities. */
            if (Auth::user()->idPerm == 4 && Inscrire::where("idUsers", $idUsers)->count() < 3) {
                /* Creating a new instance of the Inscrire model and saving it to the database. */
                $inscrit = new Inscrire;
                $inscrit->idUsers = $idUsers;
                $inscrit->codeType = $codeType;
                $inscrit->idActivite = $idActivite;
                $inscrit->save();
            }
        }

        return redirect("/liste-activite");
    }

    /*
 *  This function is used to delete a user from an activity
 */
    function DesinscriptionActivite($idUsers, $codeType, $idActivite)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        if (Auth::user()->idPerm != 4) {
            return redirect('/');
        }

        /* Checking if the activity exists. */
        if (!Activite::where('codeType', $codeType)->where('idActivite', $idActivite)->exists()) {
            session()->flash('alertActivite', "Erreur : L'activité n'éxiste pas.");
            return redirect('/liste-activite');
        }

        /* Checking if the user is already registered for the activity. */
        if (!Inscrire::where('codeType', $codeType)->where('idActivite', $idActivite)->where('idUsers', $idUsers)->exists()) {
            session()->flash('alertActivite', "Erreur : Vous n'êtes pas inscrit à cette activité.");
            return redirect('/liste-activite');
        }

        $activites = Inscrire::where('codeType', $codeType)
                ->where('idUsers', $idUsers)
                ->where('idActivite', $idActivite)
                ->delete();
        
        return redirect("/liste-activite");
    }


    /**
     * > This function is used to delete a registration for an activity for a student
     */
    function DesinscriptionActivitePourListeActiviteParEleve($idUsers, $codeType, $idActivite)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        /* Checking if the user is a student. */
        if (Auth::user()->idPerm != 4) {
            return redirect('/');
        }

        /* Checking if the activity exists. */
        if (!Activite::where('codeType', $codeType)->where('idActivite', $idActivite)->exists()) {
            session()->flash('alertActivite', "Erreur : L'activité n'éxiste pas.");
            return redirect('/mes-activite');
        }

        /* Checking if the user is already registered for the activity. */
        if (!Inscrire::where('codeType', $codeType)->where('idActivite', $idActivite)->where('idUsers', $idUsers)->exists()) {
            session()->flash('alertActivite', "Erreur : Vous n'êtes pas inscrit à cette activité.");
            return redirect('/mes-activite');
        }


        Inscrire::where('codeType', $codeType)->where('idUsers', $idUsers)->where('idActivite', $idActivite)->delete();

        return redirect("/mes-activite");
    }

    /**
     * It returns a list of all the students that are registered for a course.
     * 
     */
    function listeInscriptionEleve($idUsers)
    {
        $inscrit = Inscrire::where('idUsers', '=', $idUsers)
            ->get();

        return response()->json($inscrit);
    }
}
