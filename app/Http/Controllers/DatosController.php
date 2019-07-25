<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Dato;

use Illuminate\Http\Request;

class DatosController extends Controller
{
    public function create(Request $rq)
    {
        $item = new Dato();
        $item->user_id = $rq->user_id;
        $item->datatype_id = $rq->datatype_id;
        $item->valor = $rq->valor;
        $item->save();
        $item->datatype;
        return response()->json(array(
            "success" => 1,
            "data" => $item
        ));
    }
    public function retrieve($user_id, $datatype_id = null)
    {
        if($datatype_id != null)
        {
            $items = Dato::where([
                ["user_id", "=", $user_id],
                ["datatype_id", "=", $datatype_id]
            ])->get();  
            $items->each(function($item){
                return $item->datatype;
            });
            return response()->json(array(
                "success" => 1,
                "data" => $items
            ));          
        }
        $items = Dato::where([
            ["user_id", "=", $user_id]
        ])->get();          
        $items->each(function($item){
            return $item->datatype;
        });
        return response()->json(array(
            "success" => 1,
            "data" => $items
        ));   
    }
    public function update(Request $rq, $id)
    {
        try{
            $item = Dato::findOrFail($id);
            $item->user_id = $rq->user_id;
            $item->datatype_id = $rq->datatype_id;
            $item->valor = $rq->valor;
            $item->save();
            $item->datatype;
        }
        catch(ModelNotFoundException $ex){
            return response()->json(array(
                "success" => 0,
                "mensaje" => "No se encontró el registro"
            ));
        }
        return response()->json(array(
            "success" => 1,
            "data" => $item
        ));
    }
    public function delete($id)
    {
        try{
            $item = Dato::findOrFail($id);
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
