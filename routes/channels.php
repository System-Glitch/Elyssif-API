<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('file.{file}', function ($user, \App\Models\File $file) {
    return $user->id == $file->recipient_id;
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return $user->id == $userId;
});
