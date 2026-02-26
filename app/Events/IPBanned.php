<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IPBanned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The banned IP address.
     *
     * @var string
     */
    public $ipAddress;

    /**
     * The reason for the ban.
     *
     * @var string
     */
    public $reason;

    /**
     * Create a new event instance.
     */
    public function __construct(string $ipAddress, string $reason = 'Multiple failed attempts')
    {
        $this->ipAddress = $ipAddress;
        $this->reason = $reason;
    }
}
