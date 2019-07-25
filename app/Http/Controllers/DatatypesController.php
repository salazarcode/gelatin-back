<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Datatype;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DatatypesController extends Controller
{
    public function create(Request $rq)
    {
        $reg = new Datatype();
        $reg->titulo = $rq->titulo;
        $reg->save();

        return response()->json(array(
            "success" => 1,
            "data" => $reg
        ));
    }
    public function retrieve($id = null)
    {
        $res = null;
        if($id != null)
        {
            try{
                $res = Datatype::findOrFail($id);
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
                "data" => Datatype::all()
            ));
        }
    }
    public function update(Request $rq, $id)
    {
        try{
            $item = Datatype::findOrFail($id);
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
            $item = Datatype::findOrFail($id);
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
