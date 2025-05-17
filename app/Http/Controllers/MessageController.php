<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
{
    $request->validate(['body' => 'required|string|max:5000']);

    abort_unless(
        $request->user()->id === $conversation->employer_id
        || $request->user()->id === $conversation->candidate_id,
        403
    );

    $message = $conversation->messages()->create([
        'sender_id' => $request->user()->id,
        'body'      => $request->body,
    ]);

    broadcast(new MessageSent($message))->toOthers();

    return $message;
}

}
