<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

class RolesController extends Controller
{
    public function create(Request $req)
    {
        $role = new Role();
        $role->titulo = $req["titulo"];
        $role->save();

        return response()->json($role);
    }

    public function retrieve($id = null)
    {
        return response()->json($id == null ? Role::all() : Role::findOrFail($id));
    }

    public function update(Request $req, $id)
    {
        $role = Role::findOrFail($id);
        $role->titulo = $req["titulo"];
        $role->save();
        return response()->json("Actualizado con éxito");
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json("Eliminado con éxito");
    }
}
