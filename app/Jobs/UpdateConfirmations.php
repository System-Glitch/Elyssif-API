<?php

namespace App\Jobs;

use App\Events\TransactionNotification;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

        $chunks = $txids->pluck('txid')->chunk(self::CHUNK_SIZE);

        if(count($chunks)) {
            foreach($chunks as $chunk) {
                $updated = Transaction::whereNotIn('txid', $chunk)->where('confirmed', 0)->select('id', 'file_id', 'txid')->get();
                Transaction::whereNotIn('txid', $chunk)->update(['confirmed' => 1]);

                foreach($updated as $tx) {
                    event(new TransactionNotification($tx->file_id, $tx->txid));
                }
            }
        } else {
            $updated = Transaction::where('confirmed', 0)->select('id', 'file_id', 'txid')->get();
            Transaction::where('confirmed', 0)->update(['confirmed' => 1]);

            foreach($updated as $tx) {
                event(new TransactionNotification($tx->file_id, $tx->txid));
            }
        }
    }
}
