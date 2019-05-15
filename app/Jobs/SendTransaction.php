<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Events\TransactionNotification;

/**
 * Create transactions.
 * (only Elyssif -> User)
 * @author Jérémy SVENSSON
 *
 */

class SendTransaction {

    /**
     * @var string
     */
    protected $address;

    /**
     * @var double
     */
    protected $amount;

    /**
     * If true, the transactional fees are deducted from the
     * amount sent.
     * If false, the fees are added to the amount
     * sent, making the transactional cost to be
     * equal to 'amount + fees'.
     *
     * @var boolean
     */
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
        $txid = bitcoind()->sendToAddress($this->address, $this->amount, null, null, $this->feesDeducted)->result();

        // Ajouter recherche de la tx dans le réseau pour validation
    }
}