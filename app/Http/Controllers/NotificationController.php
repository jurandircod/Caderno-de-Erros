<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $message;
    private $type; // success, error, info, warning
    private $duration; // in seconds

    public static function redirectWithNotification($route, $message, $type = 'info')
    {
        return redirect()->route($route)
            ->with('notification', [
                'message' => $message,
                'type' => $type,
            ]);
    }
}
