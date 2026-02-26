<?php

namespace App\Events;

use App\Models\Subscriptions;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionPaid
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The subscription that was paid.
     *
     * @var Subscriptions
     */
    public $subscription;

    /**
     * Create a new event instance.
     */
    public function __construct(Subscriptions $subscription)
    {
        $this->subscription = $subscription;
    }
}
