<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Animer;
use App\Models\Activite;
use App\Models\Inscrire;
use App\Models\Categorie;
use App\Models\Intervenant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ActiviteController extends Controller
{

    public function listeGlobalActivite()
    {

        $listeActivite = Activite::with('type')->with('categorie')
            ->leftJoin('inscrire', function ($join) {
                $join->on('activite.codeType', '=', 'inscrire.codeType')
                    ->where(DB::raw('activite.idActivite'), '=', DB::raw('inscrire.idActivite'));
            })
            ->select(
                'activite.codeType',
                'activite.idActivite',
                DB::raw('MAX(activite.idCategorie) as idCategorie'),
                'activite.momentActivite',
                'activite.dateActivite',
                'activite.dateActivite as dateStamp',
                'activite.heureDebutActivite',
                'activite.heureFinActivite',
                'activite.lieu',
                'activite.titreActivite',
                'activite.nbPlaceActivite',
                'activite.descriptionActivite',
                'activite.enteteActivite',
                'activite.publicActivite',
                DB::raw('concat( activite.codeType, activite.idActivite) as codeActivite'),
                DB::raw('count(inscrire.idUsers) as total'),
                DB::raw('(activite.nbPlaceActivite - count(inscrire.idUsers)) as placeRestante'),
                'activite.activiteAnnule',

            )
            ->groupBy('activite.codeType', 'activite.idActivite', 'activite.idCategorie', 'activite.momentActivite', 'activite.dateActivite', 'activite.heureDebutActivite', 'activite.heureFinActivite', 'activite.titreActivite', 'activite.nbPlaceActivite', 'activite.descriptionActivite', 'activite.enteteActivite', 'activite.publicActivite', 'activite.lieu', 'activite.activiteAnnule')
            ->get();


        return response()->json($listeActivite);
    }

    /**
     * > This function returns a list of all activities for a user
     */
    function listeGlobalActiviteForUser()
    {
        $listeActivite = Inscrire::with('activite')->get();
        return response()->json($listeActivite);
    }


    /**
     * > This function redirects the user to the listeActivite page
     */
    public function RedirectListeActivite()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        if (Auth::user()->idPerm != 4) {
            return redirect('/');
        }
        return view("eleve/listeActivite", ['urlAPI' => url('/')]);
    }
    /**
     * > This function redirects the user to the listeActiviteParEleve view
     */
    public function RedirectlisteActiviteParEleve()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        if (Auth::user()->idPerm != 4) {
            return redirect('/');
        }
        return view("eleve/listeActiviteParEleve", ['urlAPI' => url('/')]);
    }


    function listeActiviteEleve($idUsers)
    {

        $resultats = Activite::with('type')->with('categorie')
            ->join('inscrire', function ($join) use ($idUsers) {
                $join->on('activite.codeType', '=', 'inscrire.codeType')
                    ->on('activite.idActivite', '=', 'inscrire.idActivite')
                    ->where('inscrire.idUsers', '=', $idUsers);
            })
            ->select(
                'activite.codeType',
                'activite.idActivite',
                DB::raw('MAX(activite.idCategorie) as idCategorie'),
                'activite.momentActivite',
                'activite.dateActivite',
                'activite.dateActivite as dateStamp',
                'activite.heureDebutActivite',
                'activite.heureFinActivite',
                'activite.lieu',
                'activite.titreActivite',
                'activite.nbPlaceActivite',
                'activite.descriptionActivite',
                'activite.enteteActivite',
                'activite.publicActivite',
                DB::raw('concat( activite.codeType, activite.idActivite) as codeActivite'),
                DB::raw('count(inscrire.idUsers) as total'),
                DB::raw('(activite.nbPlaceActivite - count(inscrire.idUsers)) as placeRestante '),
                'activite.activiteAnnule',
            )
            ->groupBy('activite.codeType', 'activite.idActivite', 'activite.idCategorie', 'activite.momentActivite', 'activite.dateActivite', 'activite.heureDebutActivite', 'activite.heureFinActivite', 'activite.titreActivite', 'activite.nbPlaceActivite', 'activite.descriptionActivite', 'activite.enteteActivite', 'activite.publicActivite', 'activite.lieu', 'activite.activiteAnnule')
            ->get();

        return response()->json($resultats);
    }

    public function addActivite(Request $request)
    {

        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }

        /* Validating the input. */
        $validator = Validator::make($request->all(), [
            'titreActivite' => ['required', 'max:128', 'string'],
            'typeActivite' => ['required'],
            'categorieActivite' => ['required'],
            'enteteActivite' => ['required', 'max:255', 'string'],
            'descriptionActivite' => ['required', 'max:1024', 'string'],
            'dateActivite' => ['required', 'date', 'after_or_equal:today'],
            'heureDebutActivite' => ['required', 'date_format:H:i'],
            'heureFinActivite' => ['required', 'date_format:H:i', 'after:heureDebutActivite'],
            'lieuActivite' => ['required', 'string', 'max:1024'],
            'nbPlacesActivite' => ['required', 'numeric'],
            'intervenantActivite' => ['required'],
            'matinOuAprem' => ['required', 'boolean'],
            'publicActivite' => ['required', 'string'],
        ], [
            'required' => 'Le champ :attribute est obligatoire',
            'numeric' => 'Le champ :attribute doit être un nombre',
            'string' => 'Le champ :attribute doit être une chaîne de caractères',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères',
            'date' => 'Le champ :attribute doit être une date valide',
            'after_or_equal' => 'Le champ :attribute doit être une date supérieure ou égale à la date d\'aujourd\'hui',
            'date_format' => 'Le champ :attribute doit respecter le format H:i',
            'after' => 'Le champ :attribute doit être une heure supérieure à l\'heure de début',
            'boolean' => 'Le champ :attribute doit être un boolean',
        ]);

        /* Redirecting the user back to the register page with the errors and the input. */
        if ($validator->fails()) {
            return redirect("management/event/activite")
                ->withErrors($validator->errors())
                ->withInput();
        }


        if ($request->typeActivite == null) {
            session()->flash('alertActivite', "Une erreur s'est produite, Aucun type n'est séléctionné.");
            return redirect("/management/event/activite");
        }


        if ($request->categorieActivite == null) {
            session()->flash('alertActivite', "Une erreur s'est produite, Aucune categorie n'est séléctionné.");
            return redirect("/management/event/activite");
        }


        if (!Type::where('codeType', $request->typeActivite)->exists()) {
            session()->flash('alertActivite', "Une erreur s'est produite, le type n'existe pas.");
            return redirect("/management/event/activite");
        }


        if (!Categorie::where('idCategorie', $request->categorieActivite)->exists()) {
            session()->flash('alertActivite', "Une erreur s'est produite, la catégorie n'existe pas.");
            return redirect("/management/event/activite");
        }


        foreach ($request->intervenantActivite as $inter) {
            if (!Intervenant::where('idIntervenant', $inter)->exists()) {
                session()->flash('alertActivite', "Une erreur s'est produite, l'intervenant n'existe pas.");
                return redirect("/management/event/activite");
            }
        }



        /* Getting the max idActivite from the Activite table where the codeType is equal to the
        typeActivite from the request. */
        $idAct = Activite::where("codeType", $request->typeActivite)->max("idActivite") + 1;




        // Création de l'activité
        $act = new Activite();
        $act->idActivite = $idAct;
        $act->idCategorie = $request->categorieActivite;
        $act->momentActivite = $request->matinOuAprem;
        $act->dateActivite = strtotime($request->dateActivite);
        $act->heureDebutActivite = strtotime($request->dateActivite . " " . $request->heureDebutActivite);
        $act->heureFinActivite = strtotime($request->dateActivite . " " . $request->heureFinActivite);
        $act->titreActivite = $request->titreActivite;
        $act->lieu = $request->lieuActivite;
        $act->nbPlaceActivite = $request->nbPlacesActivite;
        $act->descriptionActivite = $request->descriptionActivite;
        $act->enteteActivite = $request->enteteActivite;
        $act->publicActivite = $request->publicActivite;
        $act->codeType = $request->typeActivite;



        // Enregistrement de l'activité
        $act->save();



        if ($request->intervenantActivite != null) {
            foreach ($request->intervenantActivite as $intervenant) {
                $animateur =  new Animer;
                $animateur->idActivite = $idAct;
                $animateur->idIntervenant = $intervenant;
                $animateur->codeType = $request->typeActivite;
                $animateur->save();
            }
        }




        /* Saving the data from the form into the session. */
        session()->flash('reNew', true);
        session()->flash('titreActivite', $request->titreActivite);
        session()->flash('typeActivite', $request->typeActivite);
        session()->flash('categorieActivite', $request->categorieActivite);
        session()->flash('enteteActivite', $request->enteteActivite);
        session()->flash('descriptionActivite', $request->descriptionActivite);
        session()->flash('dateActivite', $request->dateActivite);
        session()->flash('heureDebutActivite', $request->heureDebutActivite);
        session()->flash('heureFinActivite', $request->heureFinActivite);
        session()->flash('lieuActivite', $request->lieuActivite);
        session()->flash('nbPlacesActivite', $request->nbPlacesActivite);
        session()->flash('intervenantActivite', $request->intervenantActivite);
        session()->flash('matinOuAprem', $request->matinOuAprem);
        session()->flash('publicActivite', $request->publicActivite);


        return redirect()->route('allActivite')->with('successActivite', 'L\'activité a été créée avec succès.');
    }

    public function editActivite(Request $request, $id, $type)
    {
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }
        /* Validating the input. */
        $validator = Validator::make($request->all(), [
            'titreActivite' => ['required', 'max:128', 'string'],
            'categorieActivite' => ['required'],
            'enteteActivite' => ['required', 'max:255', 'string'],
            'descriptionActivite' => ['required', 'max:1024', 'string'],
            'dateActivite' => ['required', 'date', 'after_or_equal:today'],
            'heureDebutActivite' => ['required', 'date_format:H:i'],
            'heureFinActivite' => ['required', 'date_format:H:i', 'after:heureDebutActivite'],
            'lieuActivite' => ['required', 'string', 'max:1024'],
            'nbPlacesActivite' => ['required', 'numeric'],
            'intervenantActivite' => ['required'],
            'matinOuAprem' => ['required', 'boolean'],
            'publicActivite' => ['required', 'string'],
        ], [
            'required' => 'Le champ :attribute est obligatoire',
            'numeric' => 'Le champ :attribute doit être un nombre',
            'string' => 'Le champ :attribute doit être une chaîne de caractères',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères',
            'date' => 'Le champ :attribute doit être une date valide',
            'after_or_equal' => 'Le champ :attribute doit être une date supérieure ou égale à la date d\'aujourd\'hui',
            'date_format' => 'Le champ :attribute doit respecter le format H:i',
            'after' => 'Le champ :attribute doit être une heure supérieure à l\'heure de début',
            'boolean' => 'Le champ :attribute doit être un boolean',
        ]);




        /* Redirecting the user back to the register page with the errors and the input. */
        if ($validator->fails()) {
            return redirect("management/event/activite")
                ->withErrors($validator->errors())
                ->withInput();
        }

        
        if ($type == null) {
            session()->flash('alertActivite', "Une erreur s'est produite, Aucun type n'est séléctionné.");
            return redirect("/management/event/activite");
        }


        if ($request->categorieActivite == null) {
            session()->flash('alertActivite', "Une erreur s'est produite, Aucune categorie n'est séléctionné.");
            return redirect("/management/event/activite");
        }


        if (!Type::where('codeType', $type)->exists()) {
            session()->flash('alertActivite', "Une erreur s'est produite, le type n'existe pas.");
            return redirect("/management/event/activite");
        }


        if (!Categorie::where('idCategorie', $request->categorieActivite)->exists()) {
            session()->flash('alertActivite', "Une erreur s'est produite, la catégorie n'existe pas.");
            return redirect("/management/event/activite");
        }


        foreach ($request->intervenantActivite as $inter) {
            if (!Intervenant::where('idIntervenant', $inter)->exists()) {
                session()->flash('alertActivite', "Une erreur s'est produite, l'intervenant n'existe pas.");
                return redirect("/management/event/activite");
            }
        }

        
        if (Activite::where('idActivite', $id)->exists()) {
            $dateActivite = strtotime($request->dateActivite);
            $heureDebut = strtotime($request->dateActivite . " " . $request->heureDebutActivite);
            $heureFin = strtotime($request->dateActivite . " " . $request->heureFinActivite);
            
            Activite::where('idActivite', $id)->update([
                'titreActivite' => $request->titreActivite,
                'idCategorie' => $request->categorieActivite,
                'enteteActivite' => $request->enteteActivite,
                'descriptionActivite' => $request->descriptionActivite,
                'dateActivite' => $dateActivite,
                'heureDebutActivite' => $heureDebut,
                'heureFinActivite' => $heureFin,
                'lieu' => $request->lieuActivite,
                'nbPlaceActivite' => $request->nbPlacesActivite,
                'momentActivite' => $request->matinOuAprem,
                'publicActivite' => $request->publicActivite,
            ]);
            session()->flash('successActivite', 'L\'activité a bien été éditée.');
            Animer::where('idActivite', $id)->where('codeType', $type)->delete();
            if ($request->intervenantActivite != null) {
                foreach ($request->intervenantActivite as $intervenant) {
                    $animateur =  new Animer;
                    $animateur->idActivite = $id;
                    $animateur->idIntervenant = $intervenant;
                    $animateur->codeType = $type;
                    $animateur->save();
                }
            }
        } else {
            session()->flash('alertActivite', "Une erreur s'est produite, la catégorie n'existe pas.");
        }
        return redirect("/management/event/activite");
    }

    public function deleteActivite($type, $id)
    {
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }

        if (!Activite::where('idActivite', $id)->where('codeType', $type)->exists()) {
            session()->flash('alertActivite', "Une erreur s'est produite, l'activité n'existe pas.");
            return redirect("/management/event/activite");
        }

        if (Activite::where('idActivite', $id)->where('codeType', $type)->where("activiteAnnule", 1)->exists()) {
            session()->flash('alertActivite', "Une erreur s'est produite, l'activité est déjà annulé.");
            return redirect("/management/event/activite");
        }

        $titreAct = "";

        $activiteTitre = Activite::select('titreActivite')->where('idActivite', $id)->where('codeType', $type)->get();



        foreach ($activiteTitre as $titre) {
            $titreAct = "(Annulé) - " . $titre->titreActivite;
        }

        if (Inscrire::where('idActivite', $id)->where('codeType', $type)->exists()) {
            Activite::where('idActivite', $id)->where('codeType', $type)->update(['titreActivite' => $titreAct, 'activiteAnnule' => 1]);
            session()->flash('successActivite', "L'activite a bien été annulé.");
        } else {
            Animer::where('idActivite', $id)->where('codeType', $type)->delete();
            Activite::where('idActivite', $id)->where('codeType', $type)->delete();
            session()->flash('successActivite', "L'activité a bien été supprimé.");
        }
        return redirect("management/event/activite");
    }
}
