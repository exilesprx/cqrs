<?php

namespace CQRS\Broadcasts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\UuidInterface;

/**
 * Class BroadcastUserError
 * @package CQRS\Broadcasts
 */
class BroadcastUserError implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * BroadcastUserError constructor.
     * @param string $message
     * @param UuidInterface|null $id
     */
    public function __construct(string $message, UuidInterface $id = null)
    {
        $this->message = $message;

        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('users.error');
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->id,
            'message' => $this->message
        ];
    }
}