<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use App\Http\Controllers\inertia;

class ConversationController extends Controller
{
    public function index(Request $request)
{
    $conversations = $request->user()->conversations()
        ->with(['employer:id,name,profile_picture',
                'candidate:id,name,profile_picture',
                'messages' => fn($q) => $q->latest()->take(1)])
        ->latest('updated_at')
        ->get();

    return inertia('Chat/ConversationList', compact('conversations'));
}

public function store(Request $request, User $candidate)
{
    abort_unless($request->user()->role === 'employer', 403);

    $conversation = Conversation::firstOrCreate([
        'employer_id'  => $request->user()->id,
        'candidate_id' => $candidate->id,
        'job_id'       => $request->job_id, // optional
    ]);

    return redirect()->route('conversations.show', $conversation);
}
}
