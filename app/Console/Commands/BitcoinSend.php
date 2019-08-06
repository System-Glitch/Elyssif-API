<?php
/*
 * Elyssif-API
 * Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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
