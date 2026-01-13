<?php

namespace App\Services;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Throwable;

class FcmService
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Send a notification to a specific device token.
     * 
     * @param string $token
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendNotification(string $token, string $title, string $body, array $data = []): bool
    {
        try {
            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);

            return true;
        } catch (Throwable $e) {
            // Log error or handle exception
            report($e);
            return false;
        }
    }
}
