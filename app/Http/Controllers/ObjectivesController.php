<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Objective;

class ObjectivesController extends Controller
{
    public function create(Request $req)
    {
        $obj = new Objective();
        $obj->titulo = $req["titulo"];
        $obj->save();

        return response()->json($obj);
    }

    public function retrieve($id = null)
    {
        return response()->json($id == null ? Objective::all() : Objective::findOrFail($id));
    }

    public function update(Request $req, $id)
    {
        $obj = Objective::findOrFail($id);
        $obj->titulo = $req["titulo"];
        $obj->save();
        return response()->json("Actualizado con éxito");
    }

    public function delete($id)
    {
        $obj = Objective::findOrFail($id);
        $obj->delete();
        return response()->json("Eliminado con éxito");
    }
}
