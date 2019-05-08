<?php

namespace App\Jobs;

//use Illuminate\Bus\Queueable;
//use Illuminate\Queue\SerializesModels;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Transaction;
use App\Events\TransactionNotification;

/**
 * Create transactions.
 * (only Elyssif -> User)
 * @author Jérémy SVENSSON
 *
 */

class SendTransactions {

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
    public function handle(string $address, double $amount, boolean $feesDeducted)
    {
        $bitcoind = bitcoind();
        
        //Elyssif Wallet must be unlocked for this to work
        $txid = $bitcoind->sendToAddress($address, $amount, $feesDeducted)->result();
    }
}