<?php

namespace Tests\Unit;

use Tests\TestCase;

class BitcoinTest extends TestCase
{
    /**
     * Test the helper accessibility.
     *
     * @return void
     */
    public function testHelper()
    {
        $bitcoind = bitcoind();
        $this->assertNotNull($bitcoind);
    }
    
    /**
     * Test if RPC calls are working by generating
     * a new address and checking it.
     *
     * @return void
     */
    public function testRPC()
    {
        $bitcoind = bitcoind();
        $address = $bitcoind->getNewAddress()->result();
        $this->assertNotEmpty($address);
        
        $info = $bitcoind->getAddressInfo($address)->result();
        $this->assertNotNull($info);
    }
}