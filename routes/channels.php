<?php

use App\Models\Notification;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('tasks.{project}', function ($user, Project $project) {
   return $project->participants->contains($user->id);
});

// Broadcast::channel('notifications.{notification}', function ($user, Notification $notification) {
//     return $user->id === $notification->user_id;
// });

Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});