<?php

namespace App\Jobs;

use App\Events\TransactionNotification;
use App\Repositories\FileRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateTransaction
{
    use Dispatchable;

    /**
     * The txid of the received transaction.
     * @var string
     */
    private $txid;
    
    /**
     * Create a new job instance.
     *
     * @param  string $txid
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
    public function handle(TransactionRepository $txRepository, FileRepository $fileRepository)
    {
        if(!$txRepository->existsByTxId($this->txid)) {

            $tx = bitcoind()->getTransaction($this->txid);
            $details = $tx->get('details');
            $addresses = [];

            foreach ($details as $vout) {
                $address = $vout['address'];

                if(!array_key_exists($address, $addresses)) {
                    $file = $fileRepository->getByAddress($address, ['id', 'address']);
                    if($file != null) {
                        $addresses[$address] = [];
                        $addresses[$address]['file'] = $file;
                        $addresses[$address]['amount'] = 0;
                    }
                }

                if(array_key_exists($address, $addresses)) {
                    $addresses[$address]['amount'] += $vout['amount'];
                }
            }

            foreach ($addresses as $address => $vout) {
                $txRepository->store([
                    'txid' => $this->txid,
                    'file_id' => $vout['file']->id,
                    'confirmed' => 0,
                    'amount' => $vout['amount']
                ]);
                event(new TransactionNotification($vout['file']->id, $this->txid));
            }
        }
    }
}
