<?php
function bitcoind()
{
    return new Denpa\Bitcoin\Client('http://'.env('RPC_USER').':'.env('RPC_PASSWORD').'@'.env('BITCOIND_HOST'));
}