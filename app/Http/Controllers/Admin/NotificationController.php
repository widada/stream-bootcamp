<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();

        return view('admin.notifications', ['notifications' => $notifications]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string'
        ]);

        $title = $request->title;
        $body = $request->body;

        $response = $this->pushNotification($title, $body);

        $responseBody = json_decode($response->body());
        
        $status = isset($responseBody->publishId) ? 'success' : 'failed';

        Notification::create([
            'title' => $title,
            'body' => $body,
            'response' => $response->body(),
            'status' => $status
        ]);

        return redirect()->back();
    }

    private function pushNotification($title, $body)
    {
        $instanceId = env('PUSHER_INSTANCE_ID');
        $secretKey = env('PUSHER_SECRET_KEY');

        $url = 'https://'.$instanceId.'.pushnotifications.pusher.com/publish_api/v1/instances/'.$instanceId.'/publishes';

        $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$secretKey,
                'Content-Type' => 'application/json'
            ])
            ->post($url, [
                'interests' => ['hello'],
                'web' => [
                    'notification' => [
                        'title' => $title,
                        'body' => $body
                    ]
                ]
            ]);

        return $response;
    }
}
