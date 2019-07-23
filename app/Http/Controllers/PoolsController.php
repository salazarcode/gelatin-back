<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pool;

class PoolsController extends Controller
{
    public function create(Request $req)
    {
        $pool = new Pool();
        $pool->titulo = $req["titulo"];
        $pool->save();

        return response()->json($pool);
    }

    public function retrieve($id = null)
    {
        return response()->json($id == null ? Pool::all() : Pool::findOrFail($id));
    }

    public function update(Request $req, $id)
    {
        $pool = Pool::findOrFail($id);
        $pool->titulo = $req["titulo"];
        $pool->save();
        return response()->json("Actualizado con éxito");
    }

    public function delete($id)
    {
        $pool = Pool::findOrFail($id);
        $pool->delete();
        return response()->json("Eliminado con éxito");
    }
}
