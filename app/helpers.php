<?php
function bitcoind()
{
    return new Denpa\Bitcoin\Client('http://'.env('RPC_USER').':'.env('RPC_PASSWORD').'@'.env('BITCOIND_HOST'));
}

function minPrice()
{
    return floatval(env('ELYSSIF_FEES', 0.0004)) + floatval(env('MIN_SELLER_PROFIT', 0.0004)) + floatval(env('BITCOIN_FEES', 0.0000332));
}

function checkBitcoinAddress($address)
{
	try {
        return bitcoind()->getAddressInfo($address)->result() != null;
    } catch(Denpa\Bitcoin\Exceptions\BadRemoteCallException $e) {
        return false;
    }
}