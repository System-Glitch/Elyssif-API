<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Transaction;

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
        $confirmations = env('MIN_CONFIRMATIONS', 3) - 1; // -1 to remove inclusion
        $bitcoind = bitcoind();
        $blockCount = $bitcoind->getBlockCount()->result();
        $hash = $bitcoind->getBlockHash($blockCount - $confirmations)->result();
        $unconfirmed = $bitcoind->listSinceBlock($hash)->result();

        $txids = collect($unconfirmed['transactions'])->filter(function($tx, $key) {
            // Filter negative amount (means it's a send transaction, not receive)
            return $tx['amount'] > 0;
        });

        $chunks = $txids->chunk(self::CHUNK_SIZE);

        foreach($chunks as $chunk) {
            Transaction::whereNotIn('txid', $chunk)->update(['confirmed' => 1]);
        }
    }
}
