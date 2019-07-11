<?php

namespace App\Console\Commands;

use App\Jobs\SendToAddress;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class BitcoinSend extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:send {address} {amount} {feesDeducted}';

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

        if(strtolower($this->argument('feesDeducted')) == "true" || strtolower($this->argument('feesDeducted')) == "false") {
            $feesDeducted = strtolower($this->argument('feesDeducted')) == "true";
        } else {
            $this->output->error("Third argument must be a boolean (".$this->argument('feesDeducted').")");
            return;
        }

        $amount = floatval($this->argument('amount'));
        if(!$amount) {
            $this->output->error("Second argument must be a float and be superior to zero (".$this->argument('amount').")");
            return;
        }

        dispatch(new SendToAddress($this->argument('address'), $this->argument('amount'), $feesDeducted));
    }
}
