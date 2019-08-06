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
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Exception;

/**
 * Check for confirmed transactions.
 * @author Jérémy LAMBERT
 *
 */
class UpdateConfirmations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const CHUNK_SIZE = 100;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $res = $client->get(env('ECHO_HOST').'/apps/'.env('ECHO_APP').'/channels', ['query' =>  ['auth_key' => env('ECHO_KEY')]]);
        if($res->getStatusCode() == 200) {
            $channels = json_decode($res->getBody())->channels;

            $fileChannels = collect($channels)->filter(function($ch, $key) {
                return Str::startsWith($key, 'private-file.'); // Keep file channels only
            });

            foreach ($fileChannels as $key => $ch) {
                if($ch->occupied && $ch->subscription_count > 0) {
                    $fileId = Str::replaceFirst('private-file.', '', $key);
                    event(new TransactionNotification($fileId));
                }
            }
        } else throw new Exception('Laravel Echo channels info request returned '.$res->getStatusCode());
    }
}
