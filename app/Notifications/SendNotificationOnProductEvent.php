<?php

namespace App\Notifications;

use App\Models\Package\PackageProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use JsonException;
use NotificationChannels\Telegram\TelegramMessage;

class SendNotificationOnProductEvent extends Notification
{
    use Queueable;

    private PackageProduct $packageProduct;

    private bool $isAdded;

    /**
     * Create a new notification instance.
     */
    public function __construct(PackageProduct $packageProduct, bool $isAdded)
    {
        $this->packageProduct = $packageProduct;
        $this->isAdded = $isAdded;
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
            ->line("Пакувальник: *{$this->packageProduct->package->packer->name}*")
            ->lineIf($this->isAdded, 'Додано:')
            ->lineIf(!$this->isAdded, 'Видалено:')
            ->line("*{$this->packageProduct->product->name}*, {$this->packageProduct->quantity} (дой-пак {$this->packageProduct->pack?->title()})");
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
