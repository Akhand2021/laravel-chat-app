<?php
// routes/channels.php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
/*
 * Example 1: Check if the authenticated user's ID matches the ID in the channel name.
 * This is common if users can only listen to their own private channel.
 * Channel name requested: private-chat.4
 * This callback checks if $user->id is equal to 4.
 */

Broadcast::channel('private-chat.{userId}', function ($user, $userId) {
    Log::info("Trying to authorize user ID {$user->id} for private-chat.{$userId}");
    return (int) $user->id === (int) $userId;
});

// Add authorization for chat channels
Broadcast::channel('chat.{id}', function ($user, $id) {
    Log::info("Trying to authorize user ID {$user->id} for chat.{$id}");
    return (int) $user->id === (int) $id;
});

// Add authorization for double-prefixed private channels
Broadcast::channel('private-private-chat.{userId}', function ($user, $userId) {
    Log::info("Trying to authorize user ID {$user->id} for private-private-chat.{$userId}");
    return (int) $user->id === (int) $userId;
});
