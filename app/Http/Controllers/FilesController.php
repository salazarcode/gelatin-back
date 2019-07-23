<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use Storage;
use URL;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FilesController extends Controller
{   
    public static function uploadAndCreate($file, $local_uri, $width = null, $height = null){
        try {
            $info = pathinfo($file->getClientOriginalName());            
            $ext = $info['extension'];      
            $nombre = bin2hex(random_bytes(24)) . "." . $ext;    
            \Storage::disk('public')->put($nombre,  \File::get($file));  

            $file = new File();    
            $file->uri = URL::to('/') . Storage::url($nombre);    
            $file->width = $width;
            $file->height = $height;
            $file->extension = $ext;
            $file->local_uri = $local_uri;
            $file->save();       
            
        } catch (Exception $e) {
            return array(
                "success" => 0,
                "data" => $e
            );
        } 

        return array(
            "success" => 1,
            "data" => $file
        );
    }

    public function create(Request $req)
    {
        
        $resultado = $this->uploadAndCreate($req->file("file"), $req->local_uri, $req->width, $req->height);
        if($resultado["success"] == 0){
            return response()->json(array(
                "success" => 0,
                "data" => $resultado["data"]
            ));            
        }

        return response()->json(array(
            "success" => 1,
            "data" => $resultado["data"]
        ));
    }    

    public function retrieve($id){
        try{
            $file = File::findOrFail($id);
            return response()->json(array(
                "success" => 1,
                "data" => $file
            ));
        }
        catch(ModelNotFoundException $ex){
            return response()->json(array(
                "success" => 0,
                "data" => array("mensaje"=>"No encontrado")
            ));
        }
    }

    public function delete($id){
        try{
            $file = File::findOrFail($id);
            $file->delete();
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
}