<?php

namespace App\Http\Controllers;


use App\Models\Activite;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CategorieController extends Controller
{
    function listeGlobalCategorie()
    {
        $listeCategorie = Categorie::get();
        return response()->json($listeCategorie);
    }

    public function addCategorie(Request $request)
    {
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }

        /* Validating the input. */
        $validator = Validator::make($request->all(), [
            'libelleCategorie' => ['required', 'string']
        ]);




        /* Redirecting the user back to the register page with the errors and the input. */
        if ($validator->fails()) {

            return redirect("/management/event/categories")
                ->withErrors($validator->errors())
                ->withInput();
        }

        $categorie = new Categorie();
        $categorie->libCategorie = $request->libelleCategorie;
        $categorie->save();
        session()->flash('successCategorie', 'La catégorie à bien été ajouté.');
        return redirect("/management/event/categories");
    }

    public function editCategorie(Request $request, $id)
    {
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }
        /* Validating the input. */
        $validator = Validator::make($request->all(), [
            'libelle' => ['required', 'string']
        ]);



        /* Redirecting the user back to the register page with the errors and the input. */
        if ($validator->fails()) {
            session()->flash('alertCategorie', "Donnez un nom à la catégorie.");
            return redirect("/management/event/categories");
        }

        if (Categorie::where('idCategorie', $id)->exists()) {
            Categorie::where('idCategorie', $id)->update(['libCategorie' => $request->libelle]);
            session()->flash('successCategorie', 'La catégorie à bien été éditée.');
        } else {
            session()->flash('alertCategorie', "Une erreur s'est produite, la catégorie n'existe pas.");
        }
        return redirect("/management/event/categories");
    }

    public function deleteCategorie($id)
    {
        if (Auth::user()->idPerm == 3 || Auth::user()->idPerm == 4) {
            redirect('/');
        }

        if (!Categorie::where('idCategorie', $id)->exists()) {
            session()->flash('alertCategorie', "Une erreur s'est produite, la catégorie n'existe pas.");
            return redirect("/management/event/categories");
        }
        if (!Activite::where('idCategorie', $id)->exists()) {
            Categorie::where('idCategorie', $id)->delete();
            session()->flash('successCategorie', 'La catégorie à bien été supprimée.');
        } else {
            session()->flash('alertCategorie', "Une ou plusieurs activités utilisent cette catégorie.");
        }
        return redirect("/management/event/categories");
    }
}
