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

class MinPriceTest extends TestCase
{

    private $elyssifFees;

    private $minProfit;

    private $bitcoinFees;

    /**
     * Set temp env variables so the min price
     * calculation is deterministic in this test.
     *
     * {@inheritdoc}
     * @see \Illuminate\Foundation\Testing\TestCase::setUp()
     */
    protected function setUp()
    {
        $this->elyssifFees = env('ELYSSIF_FEES');
        $this->minProfit = env('MIN_SELLER_PROFIT');
        $this->bitcoinFees = env('BITCOIN_FEES');
        putenv('ELYSSIF_FEES=0.0004');
        putenv('MIN_SELLER_PROFIT=0.0004');
        putenv('BITCOIN_FEES=0.0000332');
    }

    /**
     * Test the min price calculation helper.
     *
     * @return void
     */
    public function testMinPrice()
    {
        $this->assertEquals(0.0008332, minPrice());
    }

    /**
     * Restore the env variables.
     *
     * {@inheritdoc}
     * @see \Illuminate\Foundation\Testing\TestCase::tearDown()
     */
    protected function tearDown()
    {
        putenv('ELYSSIF_FEES=' . $this->elyssifFees);
        putenv('MIN_SELLER_PROFIT=' . $this->minProfit);
        putenv('BITCOIN_FEES=0.0000332' . $this->bitcoinFees);
    }
}
