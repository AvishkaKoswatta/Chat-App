<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function sendMessage(Request $request)
{
    $message = $request->input('message');

    // Identify the user
    $user = auth()->check() ? User::find(auth()->id()) : null;

    // Create a unique session ID for guests
    $sessionId = session()->getId();

    // Save the message
    $savedMessage = Message::create([
        'user_id' => $user ? $user->id : null,
        'session_id' => $user ? null : $sessionId,
        'message' => $message,
        'is_admin' => false,
    ]);

    // Broadcast the message to the admin
    broadcast(new MessageSent($user, $message))->toOthers();

    return response(['status' => 'Message Sent!', 'message' => $savedMessage]);
}


public function sendAdminMessage(Request $request)
{
    $message = Message::create([
        'user_id' => $request->input('user_id'),
        'message' => $request->input('message'),
        'is_admin' => true,
    ]);

    return response(['status' => 'Message Sent!', 'message' => $message]);
}

public function fetchMessages()
{
    $user = auth()->user();
    $sessionId = session()->getId();

    $messages = Message::query()
        ->when($user, function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }, function ($query) use ($sessionId) {
            $query->where('session_id', $sessionId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

    return response()->json($messages);
}


}
