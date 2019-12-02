<?php

namespace gburtini\Distributions\Tests;

use gburtini\Distributions\Beta;
use PHPUnit\Framework\TestCase;
use SplFixedArray;

class BetaDistributionTest extends TestCase
{
    public function testBetaInstantiateDistribution()
    {
        $jeffreys = new Beta(0.5, 0.5); // corresponds to a binomial.
        $bayes = new Beta(1, 1);
    }


    public function testBetaInvalidInstantiation()
    {
        $this->setExpectedException('InvalidArgumentException');
        $haldanes = new Beta(0, 0); // 0, 0 is not a valid beta distribution (it has two inf singularities)
    }


    public function testBetaInvalidInstantiationNegative()
    {
        $this->setExpectedException('InvalidArgumentException');
        new Beta(-1, -1);
    }


    public function testBetaInvalidInstantiationNegativeOne()
    {
        $this->setExpectedException('InvalidArgumentException');
        new Beta(-1, 1);
    }

    public function testObjectDraw()
    {
        srand(1); // fix the random key, just for consistency.

        $d = new Beta(0.5, 0.5);

        $scale = 5000;
        $draws = new SplFixedArray($scale);
        for ($i = 0; $i < $scale; $i++) {
            $draws[$i] = $d->rand();
        }

        $number = array_sum((array)$draws) / count($draws);
        $this->assertEquals(0.5, $number, "Attempting to draw from B(0.5, 0.5) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);
    }

    public function testStrongEdgeCase()
    {
        srand(1); // fix the random key, just for consistency.

        $d = new Beta(1, 10000);

        $scale = 1000;
        $draws = new SplFixedArray($scale);
        for ($i = 0; $i < $scale; $i++) {
            $draws[$i] = $d->rand();
        }

        $number = array_sum((array)$draws) / count($draws);
        $this->assertEquals(1 / 10001, $number, "Attempting to draw from B(1, 10000) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);
    }

    public function testClassDraw()
    {
        srand(1);
        $scale = 5000;
        $draws = new SplFixedArray($scale);
        for ($i = 0; $i < $scale; $i++) {
            $draws[$i] = Beta::draw(0.5, 0.5);
        }
        $number = array_sum((array)$draws) / count($draws);
        $this->assertEquals(0.5, $number, "Attempting to draw statically from B(0.5, 0.5) {$scale} times gives us a value too far from the expected mean. This could be just random chance iff testObjectDraw failed too: if it didn't, this is a definite problem.", 0.01);
    }

    public function testICDF()
    {
        $d = new Beta(10, 5);
        $this->assertEquals(0.3726, $d->icdf(0.01), "Inverse CDF incorrect", 0.01);
        $this->assertEquals(0.6742, $d->icdf(0.5), "Inverse CDF incorrect", 0.01);
        $this->assertEquals(0.8981, $d->icdf(0.99), "Inverse CDF incorrect", 0.01);
    }

    public function testBetaPDFAndCDF()
    {
        /**
         * array of arrays with a,b,x,test,value
         */
        $params = [
            // alpha changes for given beta
            [0.5, 1.5, 0.5, "pdf", 0.63661977236758134308],
            [2.0, 1.5, 0.5, "pdf", 1.32582521472477660830],
            [4.0, 1.5, 0.5, "pdf", 0.87007279716313464917],
            [0.5, 1.5, 0.5, "cdf", 0.81830988618379067154],
            [2.0, 1.5, 0.5, "cdf", 0.38128156646177091615],
            [4.0, 1.5, 0.5, "cdf", 0.11887865938082554577],
            // beta changes for given alpha
            [2.0, 0.5, 0.5, "pdf", 0.53033008588991064330],
            [2.0, 2.0, 0.5, "pdf", 1.50000000000000000000],
            [2.0, 4.0, 0.5, "pdf", 1.25000000000000000000],
            [2.0, 0.5, 0.5, "cdf", 0.11611652351681559450],
            [2.0, 2.0, 0.5, "cdf", 0.50000000000000000000],
            [2.0, 4.0, 0.5, "cdf", 0.81250000000000000000],

            // nonstandard parameters
            [0.0001, 10000, 0.5, "pdf", 0],
            [0.0001, 0.0001, 0.5, "pdf", 0.00019997227932343229378],
            [10000000, 10000000, 0.5, "pdf", 3568.2481877024396040, 1e-3],
            [1, 0.1, 0.9999, "pdf", 398.10717055349725077, 1e-10],
            [1, 0.1, 0.9999, "cdf", 0.60189282944650274923, 1e-11],
            [10000000, 0.00001, 1, "cdf", 1],
        ];

        foreach ($params as $param) {
            $d = new Beta($param[0], $param[1]);
            $this->assertEquals($d->{$param[3]}($param[2]), $param[4], strtoupper($param[3]) . " incorrect", isset($param[5]) ? $param[5] : 1e-15);
        }

    }

    public function testMean()
    {
        $d = new Beta(3, 4);
        $this->assertEquals(0.42857142857142857143,$d->mean());
    }

    public function testMeanVarianceSkewnessKurtosis() {
        $d = new Beta(3,4);
        $expected = [0.42857142857142857143, 0.030612244897959183673, 0.18144368465060578505, 2.4444444444444444444];
        $values = [$d->mean(), $d->variance(), $d->skewness(), $d->kurtosis()];
        for($i = 0; $i < count($expected); $i++) {
            $this->assertEquals($expected[$i], $values[$i]);
        }
    }
}
