<?php
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
