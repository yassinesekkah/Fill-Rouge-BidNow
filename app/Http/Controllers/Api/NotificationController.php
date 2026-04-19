<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public static function createNotification($user_id, $type, $message)
    {
        Notification::create([
            'user_id' => $user_id,
            'type' => $type,
            'message' => $message,
        ]);
    }
}
