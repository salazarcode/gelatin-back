<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Detail;
use App\Http\Controllers\FilesController;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UsersController extends Controller
{
    public function register(Request $req)
    {
        $user = new User();
        $user->pool_id = 1;
        $user->role_id = $req->role_id;
        $user->email = $req->email;
        $user->password = $req->password;
        $user->session_token = "";
        $user->recovery_token = "";
        $user->push_token = "";
        $user->save();

        $d = new Detail();
        $d->user_id = $user->id;

        $d->sexo = $req->sexo;
        $d->nombre = $req->nombre;
        $d->ubicacion = $req->ubicacion;
        $d->edad = $req->edad;

        $d->estatura = $req->estatura;
        $d->peso = $req->peso_actual;
        $d->cintura = $req->cintura;
        $d->peso_ideal = $req->peso_ideal;

        $d->intensidad_programa = 0;
        $d->actividad_fisica_actual = $req->actividad_fisica_actual;
        $d->actividad_fisica_meta = $req->actividad_fisica_meta;
        $d->profile_picture = $req->profile_picture != null ? FilesController::uploadFile($req->file("profile_picture")) : "";

        $d->save();

        $habitos = collect($req->habitos);        
        $objetivos = collect($req->objetivos);
        
        $habitos->each(function($item) use($user){
            $user->habits()->attach($item["id"]);
        });
        
        $objetivos->each(function($item) use($user){
            $user->objectives()->attach($item["id"]);
        });
        $user->detail;
        $user->habits;
        $user->objectives;
        $user->role;

        $this->create_token($user->id);

        return response()->json(array(
            "success" => 1,
            "data" => array("user" => $user)
        ));
    }

    public static function retrieve($id = null)
    {
        try{
            $user = $id != null ?  (User::findOrFail($id)) : (User::all());
            return response()->json(array(
                "success" => 1,
                "data" => $user
            ));
        }
        catch(ModelNotFoundException $ex){
            return response()->json(array(
                "success" => 0,
                "data" => array("mensaje"=>"No encontrado")
            ));
        }
    }

    public function update(Request $req, $id)
    {
        $res = $this->retrieve($id);
        if($res["success"] == 0)
        {
            return response()->json(array(
                "success" => 0,
                "message" => "Usuario no encontrado"
            ));
        }
        $user = $res["success"]["data"];

        if($req["role_id"] != null) $user->role_id = $req["role_id"];
        if($req["email"] != null) $user->email = $req["email"];
        if($req["password"] != null) $user->password = $req["password"];
        if($req["session_token"] != null) $user->session_token = $req["session_token"];
        if($req["recovery_token"] != null) $user->recovery_token = $req["recovery_token"];

        $user->save();
        return response()->json(array(
            "success" => 1,
            "message" => "Actualizado con éxito"
        ));
    }

    public function delete($id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(array(
                "success" => 1,
                "data" => "Eliminado exitosamente"
            ));
        }
        catch(ModelNotFoundException $ex){
            return response()->json(array(
                "success" => 0,
                "data" => array("mensaje"=>"No encontrado")
            ));
        }
    }

    public function create_token($user_id)
    {
        $token = bin2hex(random_bytes(24));
        $user = User::find($user_id);
        $user->session_token = $token;
        $user->save();
        return $token;
    }

    public function login(Request $req){
        $email = $req->email;
        $password = $req->password;
        $facebook = $req->facebook;
        $gmail = $req->gmail;

        if($facebook || $gmail)
        {
            if($email)
            {
                try{
                    $user = User::where("email", $email)->firstOrFail();
                    $this->create_token($user->id);
                    $user->detail;
                    $user->habits;
                    $user->objectives;
                    $user->role;
                    return response()->json(array(
                        "success" => 1,
                        "data" => $user
                    ));
                }
                catch(ModelNotFoundException $ex){
                    return response()->json(array(
                        "success" => 0,
                        "data" => array("mensaje"=>"Usuario no existe")
                    ));
                }
            }
            else
            {
                return response()->json(array(
                    "success" => 0,
                    "data" => array(
                        "mensaje"=>"No envió un correo",
                        "input"=>$req->all()
                        )
                ));                
            }
        }
        else
        {
            if($email && $password)
            {
                try{
                    $user = User::where([
                        ['email', '=', $email],
                        ['password', '=', $password],
                    ])->firstOrFail();
                    $user->detail;                    
                    $user->habits;
                    $user->objectives;
                    $user->role;
                    return response()->json(array(
                        "success" => 1,
                        "user" => $user
                    ));
                }
                catch(ModelNotFoundException $ex){
                    return response()->json(array(
                        "success" => 0,
                        "data" => array("mensaje"=>"Usuario no existe")
                    ));
                }
            }
            else
            {
                return response()->json(array(
                    "success" => 0,
                    "data" => array("mensaje"=>"Falta el correo o el password")
                ));                
            }
        }
    }

    public function logout(Request $rq){
        $token = $rq->header("token");
        $user = User::where("session_token", $token)->first();
        if($user != null)
        {
            $user->session_token = "";
            $user->save();
            return response()->json(array(
                "success" => 1
            ));
        }
        else
            return response()->json(array(
                "success" => 0,
                "message" => "Such token doesn't exists"
            ));
    }
}
