<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Level;

class LevelsController extends Controller
{
    public function create(Request $req)
    {
        $level = new Level();
        $level->titulo = $req["titulo"];
        $level->save();

        return response()->json($level);
    }

    public function retrieve($id = null)
    {
        return response()->json($id == null ? Level::all() : Level::findOrFail($id));
    }

    public function update(Request $req, $id)
    {
        $level = Level::findOrFail($id);
        $level->titulo = $req["titulo"];
        $level->save();
        return response()->json("Actualizado con éxito");
    }

    public function delete($id)
    {
        $level = Level::findOrFail($id);
        $level->delete();
        return response()->json("Eliminado con éxito");
    }
}
