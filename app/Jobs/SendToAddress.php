<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Events\TransactionNotification;
use Illuminate\Console\ConfirmableTrait;

/**
 * Create transactions.
 * (only Elyssif -> User)
 * @author Jérémy SVENSSON
 *
 */

class SendToAddress {

    use ConfirmableTrait;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $amount;

    /**
     * If true, the transactional fees are deducted from the
     * amount sent.
     * If false, the fees are added to the amount
     * sent, making the transactional cost to be
     * equal to 'amount + fees'.
     *
     * @var boolean
     */
    private $feesDeducted;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $address, string $amount, boolean $feesDeducted)
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
        if (!$this->confirmToProceed()) {
            return;
        }
        
        $txid = bitcoind()->sendToAddress($this->address, $this->amount, null, null, $this->feesDeducted)->result();
    }
}