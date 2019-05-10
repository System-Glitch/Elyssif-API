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

class SendTransaction {

	protected $address;
	protected $amount;
	protected $feesDeducted;

	/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $address, double $amount, boolean $feesDeducted)
    {
        $this->address = $address;
        $this->amount = $amount;
        $this->feesDeducted = $feesDeducted;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $txid = bitcoind()->sendToAddress($this->address, $this->amount, $this->feesDeducted)->result();

        // Ajouter recherche de la tx dans le réseau pour validation
    }
}