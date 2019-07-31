<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use Storage;
use URL;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FilesController extends Controller
{   
    public static function uploadFile($file, $local_uri, $width = null, $height = null){
        $info = pathinfo($file->getClientOriginalName());            
        $ext = $info['extension'];      
        $nombre = bin2hex(random_bytes(24)) . "." . $ext;    
        \Storage::disk('public')->put($nombre,  \File::get($file));    
        return URL::to('/') . Storage::url($nombre);
    }
}