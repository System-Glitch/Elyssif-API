<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-transaction {address} {amount} {--feesDeducted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending Bitcoin transaction on blockchain (\'sendtoaddress\')';

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
        if (!$this->confirmToProceed()) {
            return;
        }

        dispatch(new SendTransaction($this->argument('address'), $this->argument('amount'), $this->argument('feesDeducted')));
    }
}
