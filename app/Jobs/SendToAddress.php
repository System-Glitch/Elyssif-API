<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

/**
 * Create and broadcast transactions from
 * Elyssif wallet.
 * (only Elyssif -> User)
 * @author Jérémy SVENSSON
 *
 */
class SendToAddress {

    /**
     * @var string
     */
    private $address;

    /**
     * @var float
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
     * @param  string $address
     * @param  float  $amount
     * @param  bool   $feesDeducted
     * @return void
     */
    public function __construct(string $address, float $amount, bool $feesDeducted)
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

        if(app()->isLocal()) {
            Log::info('New tx: '.$txid.' ('.$this->amount.' BTC) to '.$this->address);
        }
    }
}