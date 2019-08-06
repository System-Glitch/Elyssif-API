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

use App\Models\File;
use App\Repositories\FileRepository;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast event sent when a transaction is created or confirmed.
 * @author Jérémy LAMBERT
 *
 */
class TransactionNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    /**
     * The file related to the confirmed transaction.
     * @var integer
     */
    private $fileId;

    /**
     * The unconfirmed amount for the file related to the tx.
     * @var double
     */
    public $pending;
    
    /**
     * The confirmed amount for the file related to the tx.
     * @var double
     */
    public $confirmed;
    
    /**
     * Create a new event instance. The file payment state is
     * automatically fetched using the given file id.
     *
     * @param int    $fileId  the id of the file related to the tx.
     * @return void
     */
    public function __construct(int $fileId)
    {
        $this->fileId = $fileId;
        
        $repo = new FileRepository(new File());
        $state = $repo->getPaymentState($fileId);
        
        $this->pending   = $state[ 'pending' ];
        $this->confirmed = $state['confirmed'];
    }

    /**
     * Get the id of the file related to the updated transaction.
     *
     * @return integer
     */
    public function getFileId()
    {
        return $this->fileId;
    }
    
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('file.'.$this->fileId);
    }
}
