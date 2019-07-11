<?php

namespace App\Console\Commands;

use App\Jobs\UpdateConfirmations;
use Illuminate\Console\Command;

class BitcoinConfirmations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:confirmations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update confirmed transactions';

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
        dispatch(new UpdateConfirmations());
    }
}
