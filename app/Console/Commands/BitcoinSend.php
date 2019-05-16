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

        /**
         *
         * Kept for review, will be removed soon.
         * filter_var can't be used alone, returns null for every false entry.
         *
         */
        //$feesDeducted = filter_var($this->argument('feesDeducted'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        //if($feesDeducted == NULL) {
        //    $this->output->writeln("Third argument given must be boolean ('".$this->argument('feesDeducted')."' becomes '".$feesDeducted."')");
        //} else {
        //    dispatch(new SendToAddress($this->argument('address'), $this->argument('amount'), $feesDeducted));
        //}

        if(strtolower($this->argument('feesDeducted')) == "true" || strtolower($this->argument('feesDeducted')) == "false")
        {
            $feesDeducted = filter_var($this->argument('feesDeducted'), FILTER_VALIDATE_BOOLEAN);
            dispatch(new SendToAddress($this->argument('address'), $this->argument('amount'), $feesDeducted));
        } else {
            $this->output->writeln("Third argument given must be boolean (".$this->argument('feesDeducted').")");
            return;
        }
    }
}
