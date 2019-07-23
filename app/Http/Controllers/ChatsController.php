<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chat;
use App\User;
use App\Message;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\FilesController;
use Illuminate\Database\QueryException;

class ChatsController extends Controller
{

    public $tests = false;
    public $chunkSize = 20;

    private function ok($dataArray)
    {
        return response()->json(array(
            "success" => 1,
            "data" => $dataArray
        )); 
    }
    private function fail($errorMessage = null, $errorObject = null)
    {
        $resArray = array();
        $resArray["success"] = 0;

        if($errorMessage != null)
            $resArray["message"] = $errorMessage;
        

        if($errorObject != null)
            $resArray["errorObject"] = $errorObject;

        return response()->json($resArray); 
    }

    public function getContacts()
    {
        $contacts = User::select("id", "email")->get();
        if($contacts != null)
        {
            return response()->json(array(
                "success" => 1,
                "data" => array("contacts" => $contacts)
            ));  
        }
        else
        {
            return response()->json(array(
                "success" => 0,
                "message" => "There wasn't found any contact"
            ));   
        }
    }

    public function addChat(Request $req)
    {
        //obtengo el remitente y el destinatario si existen
        try 
        {       
            $remitente = $this->tests == true ? User::where("session_token", $req->header("token"))->firstOrFail() : User::findOrFail($req->remitente_id);
            $destinatario = User::findOrFail($req->destinatario_id);
        } 
        catch (ModelNotFoundException $ex) 
        {
            return response()->json(array(
                "success" => 0,
                "message" => "One or both users do not exist."
            ));  
        }

        //Si existen creo el chat y luego le agrego cada uno de los usuarios
        $newChat = new Chat();
        $newChat->user_id = $remitente->id;
        $newChat->save();

        $newChat->users()->attach($remitente->id);
        $newChat->users()->attach($destinatario->id);

        $newChat->users;

        return response()->json(array(
            "success" => 1,
            "data" => array(
                "chat" => $newChat
            )
        ));           
    }

    public function getChats(Request $req, $chat_id = null)
    {
        if($chat_id != null)
        {
            try 
            {
                if($this->tests == false) 
                {
                    $user = User::where("session_token", $req->header("token"))->firstOrFail();
                    $chat = Chat::with("users:id,email")->findOrFail($chat_id);
                    if($chat->user_id == $user->id)                    
                        return $this->ok(array("chat"=>$chat));
                    else
                        return $this->fail("This chat doesn't belongs to you");
                }      
                else
                {
                    $chat = Chat::with("users:id,email")->findOrFail($chat_id);
                    return $this->ok( array("chat"=>$chat) );
                }                
            } 
            catch (ModelNotFoundException $ex) 
            {
                return $this->fail("Chat doesn't exist");
            }                     
        }
        else
        {
            try 
            {
                if($this->tests == false) 
                {
                    $user = User::where("session_token", $req->header("token"))->firstOrFail();
                    $chats = Chat::with("users:id,email")->where("user_id", $user->id)->get();
                    return $this->ok( array("chats"=>$chats) );
                }      
                else
                {
                    $chats = Chat::with("users:id,email")->get();
                    return $this->ok( array("chats"=>$chats) );
                }                
            } 
            catch (ModelNotFoundException $ex) 
            {
                return $this->fail("El usuario no existe");
            }   
        }
    }


    public function addMessage(Request $req, $chat_id)
    {
        //creo el mensaje con el input del usuario
        $text = $req->text != null ? $req->text : "";

        $message = new Message();
        $message->text = $text;
        $message->chat_id = $chat_id;

        $file_id = null;
        if ($req->hasFile('file') && $req->has("extension") && $req->has("local_uri") ) {
            $fileCreated = FilesController::uploadAndCreate($req->file("file"), $req->local_uri, $req->width, $req->height);
            $message->file_id = $fileCreated["data"]["id"];          
        }

        try {
            $message->user_id = $this->tests ? $req->user_id : User::where("session_token", $req->header("token"))->first()->id;
            $message->save();
        } catch (QueryException $ex) {
            return $this->fail(null, array("error" => $ex));
        }

        $messages = Chat::find($chat_id)
                        ->messages()
                        ->orderBy('created_at', 'desc')
                        ->take($this->chunkSize)
                        ->get(); 

        return $this->ok(array("messages"=>$messages));
    }

    public function getMessages(Request $request, $chat_id, $message_id = null)
    {
        if($message_id == null)
        {
            $messages = Chat::find($chat_id)->messages()->orderBy('created_at', 'desc')->take($this->chunkSize)->get(); 
            return $this->ok(array("messages"=>$messages));            
        }
        else
        {
            $messages = Chat::find($chat_id)
                            ->messages()
                            ->where('created_at', '<', Message::find($message_id)->created_at)
                            ->orderBy('created_at', 'asc')
                            ->take($this->chunkSize)
                            ->get(); 
            return $this->ok(array("messages"=>$messages));                
        }

    }
}
