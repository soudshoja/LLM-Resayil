<?php

namespace App\Events;

use App\Models\Subscriptions;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiring
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The subscription expiring.
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
