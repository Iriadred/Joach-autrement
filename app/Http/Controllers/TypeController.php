<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    function listeGlobalType()
    {

        $listeType = Type::get();


        return response()->json($listeType);
}
}
