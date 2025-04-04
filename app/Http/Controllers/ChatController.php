<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        $messages = Message::where(function($query) {
            $query->where('user_id', Auth::id())
                  ->orWhere('recipient_id', Auth::id());
        })->with(['user', 'recipient'])->get();
        
        return view('chat', compact('messages', 'users'));
    }

    public function getMessages(Request $request)
    {
        $messages = Message::where(function($query) use ($request) {
            $query->where(function($q) use ($request) {
                $q->where('user_id', Auth::id())
                  ->where('recipient_id', $request->user_id);
            })->orWhere(function($q) use ($request) {
                $q->where('user_id', $request->user_id)
                  ->where('recipient_id', Auth::id());
            });
        })->with(['user', 'recipient'])->get();

        // Mark messages as read
        Message::where('recipient_id', Auth::id())
              ->where('user_id', $request->user_id)
              ->where('is_read', false)
              ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'user_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'message' => $request->message,
            'is_read' => false
        ]);

        // Load the relationships before broadcasting
        $message->load(['user', 'recipient']);
        
        // Broadcast to all users (including sender)
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function getUnreadCount()
    {
        $count = Message::where('recipient_id', Auth::id())
                       ->where('is_read', false)
                       ->count();
        
        return response()->json(['count' => $count]);
    }
}
