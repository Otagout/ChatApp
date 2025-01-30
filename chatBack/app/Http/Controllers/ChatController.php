<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function rooms(Request $request){
        return ChatRoom::all();
    }


    public function messages(Request $request , $roomid){
        return ChatMessage::where('chat_room_id', $roomid)->with('user')
        ->orderBy('created_at','DESC')->get();


    }

    public function newMessage(Request $request, $roomid)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
    
        $newMessage = ChatMessage::create([
            'user_id' => Auth::id(),
            'chat_room_id' => $roomid,
            'message' => $request->message,
        ]);
    
        broadcast(new NewChatMessage($newMessage))->toOthers();
    
        return response()->json($newMessage, 201);
    }
    
    
}
