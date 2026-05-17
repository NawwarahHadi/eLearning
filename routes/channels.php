<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{userId}', function ($user, $userId) {
    // Return true if the logged-in user is allowed to listen to this private stream
    return (int) $user->id === (int) $userId;
});
