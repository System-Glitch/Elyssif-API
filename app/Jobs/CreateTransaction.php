<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;

class CreateTransaction
{
    use Dispatchable;

    /**
     * The txid of the received transaction.
     * @var string
     */
    private $txid;
    
    /**
     * Create a new job instance.
     *
     * @param  string $txid
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
    public function handle()
    {
        // TODO create transaction if match
    }
}
