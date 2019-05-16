<?php
namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    /**
     * The user to send the notification to.
     *
     * @var int
     */
    private $userId;

    /**
     * The message of the notification.
     * Should be a locale key.
     *
     * @var $message
     */
    public $message;

    /**
     * Generic value for the notification.
     * For example, a new file name.
     *
     * @var string
     */
    public $value;

    /**
     * Create a new event instance.
     *
     * @param $user \App\Models\User
     * @param $title string
     * @param $message string
     * @return void
     */
    public function __construct(int $userId, string $message, string $value = null)
    {
        $this->userId = $userId;
        $this->message = $message;
        $this->value = $value;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
    }
}
