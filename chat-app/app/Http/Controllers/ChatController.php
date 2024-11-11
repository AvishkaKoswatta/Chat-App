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
        $user = User::find(auth()->id());

        broadcast(new MessageSent($user, $message))->toOthers();

        return response(['status' => 'Message Sent!']);
    }
}
