<?php

namespace App\Jobs;

use App\Repositories\TransactionRepository;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Repositories\FileRepository;

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
        // TODO create transaction if match
        if(!$txRepository->existsByTxId($this->txid)) {

            $tx = bitcoind()->getTransaction($this->txid);
            Log::info($tx); // TODO remove debug
            $details = $tx->get('details');
            $addresses = [];

            foreach ($details as $vout) {
                $address = $vout['address'];

                if(!array_key_exists($address, $addresses)) {
                    $file = $fileRepository->getByAddress($address, ['id', 'address']); // TODO needs address field
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
                Log::info($address.': '.$vout['amount']);
                $fileRepository->store([
                    'file_id' => $vout['file']->id,
                    'confirmed' => 0,
                    'amount' => $vout['amount']
                ]);
            }
        }
    }
}
