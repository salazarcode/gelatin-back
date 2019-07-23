<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Habit;

class HabitsController extends Controller
{
    public function create(Request $req)
    {
        $habit = new Habit();
        $habit->titulo = $req["titulo"];
        $habit->save();

        return response()->json($habit);
    }

    public function retrieve($id = null)
    {
        return response()->json($id == null ? Habit::all() : Habit::findOrFail($id));
    }

    public function update(Request $req, $id)
    {
        $habit = Habit::findOrFail($id);
        $habit->titulo = $req["titulo"];
        $habit->save();
        return response()->json("Actualizado con éxito");
    }

    public function delete($id)
    {
        $habit = Habit::findOrFail($id);
        $habit->delete();
        return response()->json("Eliminado con éxito");
    }
}
