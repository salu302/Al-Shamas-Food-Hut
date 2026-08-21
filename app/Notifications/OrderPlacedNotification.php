<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return [];
    }

    public function buildMessage(): string
    {
        $itemsSummary = $this->order->orderItems()->with('item')->get()->map(function ($orderItem) {
            $item = $orderItem->item;
            $itemName = $item ? ($item->name_en ?: $item->name_ur ?: 'Item') : 'Item';

            return $itemName.' x'.$orderItem->quantity;
        })->implode(', ');

        return sprintf(
            'Dear %s, thank you for your order #%d at Al-Shamas Pizza Hut! Total Bill: Rs. %s. Items: %s. Payment: Cash on Delivery.',
            $this->order->customer_name,
            $this->order->id,
            $this->order->total_amount,
            $itemsSummary ?: 'No items',
        );
    }

    public function logPayload(): void
    {
        Log::info('Order confirmation SMS payload', [
            'phone' => $this->order->customer_phone,
            'message' => $this->buildMessage(),
        ]);
    }
}
