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
    public function create(Request $req)
    {
        $user = new User();
        $user->pool_id = 1;
        $user->role_id = $req["role_id"];
        $user->email = $req["email"];
        $user->password = $req["password"];
        $user->session_token = "";
        $user->session_recovery = "";
        $user->save();

        $token = $this->create_token($user->id, $user->role->id);

        return response()->json(array(
            "success" => 1,
            "data" => array("user" => $user, "token" => $token)
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
        $correo = $req->correo;
        $password = $req->password;
        $facebook = $req->facebook;
        $gmail = $req->gmail;


        if($facebook || $gmail)
        {
            if($correo)
            {
                try{
                    $user = User::where("email", $correo)->firstOrFail();
                    return response()->json(array(
                        "success" => 1,
                        "data" => array("token"=> $this->create_token($user->id))
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
                    "data" => array("mensaje"=>"No envió un correo")
                ));                
            }
        }
        else
        {
            if($correo && $password)
            {
                try{
                    $user = User::where([
                        ['email', '=', $correo],
                        ['password', '=', $password],
                    ])->firstOrFail();
                    $detail = $user->detail;
                    $file = $detail->file;
                    return response()->json(array(
                        "success" => 1,
                        "user" => $user,
                        "detail" => $detail,
                        "file" => $file
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

    public function activeUserSessions(){
        $users = User::where("session_token", "!=", "")->get();
        return $users;
    }
}
