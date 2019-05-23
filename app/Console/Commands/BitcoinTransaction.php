<?php

namespace App\Console\Commands;

use App\Jobs\CreateTransaction;
use Illuminate\Console\Command;

class BitcoinTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:transaction {txid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a transaction from the given txid.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dispatch(new CreateTransaction($this->argument('txid')));
    }
}
