<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;
use App\Models\User;

Broadcast::channel('conversation.{conversation}', function (User $user, Conversation $conversation) {
    return $user->id === $conversation->employer_id
        || $user->id === $conversation->candidate_id;
});
