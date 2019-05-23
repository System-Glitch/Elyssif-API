<?php
namespace App\Jobs;

use App\Events\TransactionNotification;
use App\Repositories\FileRepository;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateTransaction
{
    use Dispatchable;

    /**
     * The txid of the received transaction.
     *
     * @var string
     */
    private $txid;

    /**
     * Create a new job instance.
     *
     * @param string $txid
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
    public function handle(FileRepository $fileRepository)
    {
        $tx = bitcoind()->getTransaction($this->txid);
        if ($tx->get('confirmations') == 0) {

            $details = $tx->get('details');
            $addresses = [];

            foreach ($details as $vout) {
                $address = $vout['address'];

                if (! array_key_exists($address, $addresses)) {
                    $file = $fileRepository->getByAddress($address, ['id', 'address']);
                    if ($file != null) {
                        $addresses[$address] = $file;
                        event(new TransactionNotification($file->id));
                    }
                }
            }
        }
    }
}
