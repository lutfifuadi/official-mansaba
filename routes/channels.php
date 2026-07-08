<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

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

Broadcast::channel('daftar-ulang', function (User $user) {
    // Hanya user dengan role super_admin, admin, atau operator yang diizinkan
    return in_array($user->role, ['super_admin', 'admin', 'operator']);
});
