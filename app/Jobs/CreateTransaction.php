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

namespace App\Jobs;

use App\Events\TransactionNotification;
use App\Repositories\FileRepository;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateTransaction
{
    use Dispatchable;

    /**
     * The txid of the received transaction.
     *
     * @var string
     */
    private $txid;

    /**
     * Create a new job instance.
     *
     * @param string $txid
     * @return void
     */
    public function __construct(string $txid)
    {
        $this->txid = $txid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FileRepository $fileRepository)
    {
        $tx = bitcoind()->getTransaction($this->txid);
        if ($tx->get('confirmations') == 0) {

            $details = $tx->get('details');
            $addresses = [];

            foreach ($details as $vout) {
                $address = $vout['address'];

                if (! array_key_exists($address, $addresses)) {
                    $file = $fileRepository->getByAddress($address, ['id', 'address']);
                    if ($file != null) {
                        $addresses[$address] = $file;
                        event(new TransactionNotification($file->id));
                    }
                }
            }
        }
    }
}
