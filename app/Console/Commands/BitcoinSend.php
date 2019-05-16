<?php

namespace App\Console\Commands;

use App\Jobs\SendToAddress;
use Illuminate\Console\Command;

class BitcoinSend extends Command
{
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
        //$feesDeducted = filter_var($this->argument('feesDeducted'), FILTER_VALIDATE_BOOLEAN);
        //if($feesDeducted == null){
        //    $this->output->writeln("Third argument given is no boolean (".$this->argument('feesDeducted').")");
        //}
        //dispatch(new SendToAddress($this->argument('address'), $this->argument('amount'), $feesDeducted));

        if(strtolower($this->argument('feesDeducted')) == "true" || strtolower($this->argument('feesDeducted')) == "false")
        {
            $feesDeducted = filter_var($this->argument('feesDeducted'), FILTER_VALIDATE_BOOLEAN);
            dispatch(new SendToAddress($this->argument('address'), $this->argument('amount'), $feesDeducted));
        } else {
            $this->output->writeln("Third argument given is no boolean (".$this->argument('feesDeducted').")");
            return;
        }
    }
}
