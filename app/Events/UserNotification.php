<?php
/*
 * Elyssif-API
 * Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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
