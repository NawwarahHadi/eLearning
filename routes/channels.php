<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $id) {
    // This ensures only the intended receiver can hear the real-time notification
    return (int) $user->id === (int) $id;
});
