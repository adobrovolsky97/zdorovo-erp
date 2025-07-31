<?php

namespace App\Notifications;

use App\Models\TelegramNotification\TelegramNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use JsonException;
use NotificationChannels\Telegram\TelegramMessage;

class SendTelegramNotification extends Notification
{
    use Queueable;
    private TelegramNotification $telegramNotification;

    /**
     * Create a new notification instance.
     */
    public function __construct(TelegramNotification $telegramNotification)
    {
        $this->telegramNotification = $telegramNotification;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @throws JsonException
     */
    public function toTelegram(object $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->routeNotificationFor('telegram'))
            ->line($this->telegramNotification->message);
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['telegram'];
    }
}
