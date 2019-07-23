<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Chat;

class MessagesController extends Controller
{
    public function create(Request $req){
        $m = new Message();
        $m->user_id = $req->user_id;
        $m->chat_id = $req->chat_id;
        $m->text = $req->text;
        $m->file_id = $req->file_id == null ? 0 : $req->file_id;
        $m->save();
        return response()->json(array(
            "success" => 1,
            "chatMessages" => $m->chat->messages()->orderBy('updated_at', 'DESC')->get()
        ));
    }

    public function retrieve($chat_id){
        $messages = Chat::find($chat_id)->messages()->orderBy('updated_at', 'DESC')->get();
        return response()->json(array(
            "success" => 1,
            "messages" => $messages
        ));
    }
}
