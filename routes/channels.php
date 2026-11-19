<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});

Broadcast::channel('driver.{driverId}', function ($user, $driverId) {
    return (string) $user->id === (string) $driverId && $user->role === 'driver';
});
