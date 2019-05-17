<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use App\Events\TransactionNotification;

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

        if ($this->hasArgument('pending')) {
            $notif->pending = (double) $this->argument('pending');
        }

        if ($this->hasArgument('confirmed')) {
            $notif->confirmed = (double) $this->argument('confirmed');
        }

        event($notif);
    }
}
