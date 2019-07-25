<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Foodtype;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FoodtypesController extends Controller
{
    public function create(Request $rq)
    {
        $ft = new Foodtype();
        $ft->titulo = $rq->titulo;
        $ft->save();

        return response()->json(array(
            "success" => 1,
            "data" => $ft
        ));
    }
    public function retrieve($id = null)
    {
        $res = null;
        if($id != null)
        {
            try{
                $res = Foodtype::findOrFail($id);
            }
            catch(ModelNotFoundException $ex){
                return response()->json(array(
                    "success" => 0,
                    "mensaje" => "No se encontró el registro"
                ));
            }
            return response()->json(array(
                "success" => 1,
                "data" => $res
            ));
        }
        else
        {
            return response()->json(array(
                "success" => 1,
                "data" => Foodtype::all()
            ));
        }
    }
    public function update(Request $rq, $id)
    {
        try{
            $item = Foodtype::findOrFail($id);
        }
        catch(ModelNotFoundException $ex){
            return response()->json(array(
                "success" => 0,
                "mensaje" => "No se encontró el registro"
            ));
        }
        $item->titulo = $rq->titulo;
        $item->save();
        return response()->json(array(
            "success" => 1,
            "data" => $item
        ));
    }
    public function delete($id)
    {
        try{
            $item = Foodtype::findOrFail($id);
        }
        catch(ModelNotFoundException $ex){
            return response()->json(array(
                "success" => 0,
                "mensaje" => "No se encontró el registro"
            ));
        }
        $item->delete();
        return response()->json(array(
            "success" => 1,
            "mensaje" => "Eliminado con éxito"
        ));
    }
}
