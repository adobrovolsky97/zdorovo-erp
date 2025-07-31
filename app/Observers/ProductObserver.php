<?php

namespace App\Observers;

use App\Models\Product\Product;
use App\Models\TelegramNotification\TelegramNotification;
use App\Notifications\SendTelegramNotification;
use Illuminate\Support\Facades\Notification;

class ProductObserver
{
    public function updated(Product $product): void
    {
        if ($product->isDirty('qty_in_stock', 'safety_stock', 'daily_demand')) {
            $this->sendTgNotificationIfRequired($product);
        }
    }

    /**
     * @param Product $product
     * @return void
     */
    private function sendTgNotificationIfRequired(Product $product): void
    {
        if (empty($product->qty_in_stock) || empty($product->safety_stock) || empty($product->daily_demand)) {
            return;
        }

        if ($product->getCalculatedLeftover() >= $product->daily_demand * $product->safety_stock) {
            return;
        }

        // If there is already a notification for this product, created less than 3 hours ago, skip sending
        if (TelegramNotification::query()->where('type', TelegramNotification::TYPE_PRODUCT_LEFTOVER)->where('product_id', $product->id)->where('created_at', '>=', now()->subHours(3))->exists()) {
            return;
        }

        $telegramNotification = TelegramNotification::create([
            'type'       => TelegramNotification::TYPE_PRODUCT_LEFTOVER,
            'product_id' => $product->id,
            'message'    => "*$product->name* залишилось {$product->getCalculatedLeftover()} шт."
        ]);

        foreach (config('services.telegram-bot-api.recipients') as $recipient) {
            Notification::route('telegram', $recipient)->notify(new SendTelegramNotification($telegramNotification));
        }
    }
}
