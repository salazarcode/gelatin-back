<?php

namespace App\Http\Controllers;
use App\Http\Controllers\FilesController;
use App\Receta;
use App\Foodtype;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;

class RecetasController extends Controller
{
    public function create(Request $rq)
    {
        $receta = new Receta();
        $receta->user_id = $rq->user_id;
        $receta->foodtype_id = $rq->foodtype_id;
        $receta->picture = FilesController::uploadFile($rq->file("picture"), "");
        $receta->nombre = $rq->nombre;
        $receta->porciones = $rq->porciones;
        $receta->calorias = $rq->calorias;
        $receta->carbohidratos = $rq->carbohidratos;
        $receta->grasas = $rq->grasas;
        $receta->descripcion = $rq->descripcion;
        $receta->save();
        $receta->foodtype;
        return response()->json(array(
            "success" => 1,
            "data" => $receta
        ));
    }
    public function retrieve($id = null)
    {
        $res = null;
        if($id != null)
        {
            try{
                $res = Receta::findOrFail($id);
                $res->foodtype;
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
                "data" => Receta::all()
            ));
        }
    }
    public function update(Request $rq, $id)
    {
        try{
            $item = Receta::findOrFail($id);
            $item->user_id = $rq->user_id;
            $item->foodtype_id = $rq->foodtype_id;
            $item->picture = FilesController::uploadFile($rq->file("picture"), "");
            $item->nombre = $rq->nombre;
            $item->porciones = $rq->porciones;
            $item->calorias = $rq->calorias;
            $item->carbohidratos = $rq->carbohidratos;
            $item->grasas = $rq->grasas;
            $item->descripcion = $rq->descripcion;
            $item->save();
            $item->foodtype;
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
            $item = Receta::findOrFail($id);
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
