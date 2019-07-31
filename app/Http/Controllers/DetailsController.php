<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detail;
use App\User;
use App\Http\Controllers\FilesController;
use \Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class DetailsController extends Controller
{
    public function create(Request $rq){
        $d = new Detail();
        $d->user_id = $rq->user_id;

        $d->sexo = $rq->sexo;
        $d->nombre = $rq->nombre;
        $d->ubicacion = $rq->ubicacion;
        $d->edad = $rq->edad;

        $d->estatura = $rq->estatura;
        $d->peso = $rq->peso_actual;
        $d->cintura = $rq->cintura;
        $d->peso_ideal = $rq->peso_ideal;

        $d->intensidad_programa = 0;
        $d->actividad_fisica_actual = $rq->actividad_fisica_actual;
        $d->actividad_fisica_meta = $rq->actividad_fisica_meta;
        $d->profile_picture = $rq->profile_picture != null ? FilesController::uploadFile($rq->file("profile_picture")) : "";

        $d->save();

        $habitos = collect($rq->habitos);        
        $objetivos = collect($rq->objetivos);
        
        $habitos->each(function($item) use($d){
            $d->habits()->attach($item["id"]);
        });
        
        $objetivos->each(function($item) use($d){
            $d->objectives()->attach($item["id"]);
        });

        return response()->json(array(
            "success" => 1,
            "data" => $d
        ));
    }

    public function retrieve($user_id)
    {
        $success = 0;
        $data = null;

        try{
            $user = User::findOrFail($user_id);
            $detail = null;
            if($user->detail()->count() != 0)
            {
                $detail = $user->detail;                
            }
            else
            {
                throw new ModelNotFoundException();
            }
            $success = 1;
            $data = $detail;
        }
        catch(ModelNotFoundException $ex){
            $data = array(
                "mensaje" => "No encontrado"
            );
        }

        return response()->json(array(
            "success" => $success,
            "data" => $data
        ));
    }
}
