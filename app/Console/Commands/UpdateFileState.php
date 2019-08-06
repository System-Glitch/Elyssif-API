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

use App\Events\TransactionNotification;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class UpdateFileState extends Command
{

    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-file-state {fileId} {pending?} {confirmed?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast a file payment state update';

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
        if (! $this->confirmToProceed()) {
            return;
        }

        $notif = new TransactionNotification($this->argument('fileId'));

        if (!empty($this->argument('pending'))) {
            $notif->pending = (double) $this->argument('pending');
        }

        if (!empty($this->argument('confirmed'))) {
            $notif->confirmed = (double) $this->argument('confirmed');
        }

        event($notif);
    }
}
