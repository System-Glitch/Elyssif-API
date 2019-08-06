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


    public function testAddressValidity()
    {
        $address = "2MvGjZfmhtuAR7QppDp7BHCFQUe3Wk5PmRj";
        $this->assertTrue(checkBitcoinAddress($address));

        $address = "64jZfmhtuARQppDp7BHCFQUe3Wk5PmRj";
        $this->assertFalse(checkBitcoinAddress($address));
    }
}
