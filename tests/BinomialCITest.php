<?php
require_once dirname(__FILE__) . "/../src/gburtini/Distributions/Binomial.php";

use gburtini\Distributions\BinomialCI;

class BinomialCITest extends PHPUnit_Framework_TestCase
{
    public function testBinomialInstantiateDistribution()
    {
        $distribution1 = new BinomialCI(213, 0);
        $distribution1 = new BinomialCI(6, 0.4);
    }

    public function testBinomialInvalidInstantiation()
    {
        $this->setExpectedException('InvalidArgumentException');
        $invalid = new BinomialCI(7.3, 0.5);
    }

    public function testBinomialInvalidInstantiation2()
    {
        $this->setExpectedException('InvalidArgumentException');
        $invalid = new BinomialCI(4, 1.2);
    }

    public function testConfidenceIntervals()
    {
        $d = new BinomialCI(213, 0);
        $ci = $d->jeffreys(0.7);
        $this->assertEquals($ci['lower'], 0, "Jeffreys CI incorrect when probability is zero (should lock to zero).");
        $this->assertEquals($ci['upper'], 0.002515455, "Jeffreys CI upper bound incorrect when probability is zero.");

        $d = new BinomialCI(213, 1);
        $ci = $d->jeffreys(0.7);
        $this->assertGreaterThan(0, $ci["lower"]);
        $this->assertEquals(1, $ci["upper"]);
    }

    public function testMeanVarianceStandardDeviation() {
        $d = new BinomialCI(40,.02);
        $this->assertEquals(0.8, $d->mean());
        $this->assertEquals(0.784, $d->variance());
        $this->assertEquals(0.88543774484714621296, $d->sd());
    }

    public function testJeffreysOutOfRange() {
        $d = new BinomialCI(40,.02);
        $this->setExpectedException('InvalidArgumentException');
        $invalid = $d->jeffreys(-5);
    }

    public function testICDFOutOfRange() {
        $d = new BinomialCI(40,.02);
        $this->setExpectedException('InvalidArgumentException');
        $invalid = $d->icdf(-5);
    }

    public function testICDFInf() {
        $d = new BinomialCI(40,.02);
        $this->assertEquals(INF, $d->icdf(1));
    }

    public function testICDF() {
        $d = new BinomialCI(40,.02);
        $this->assertEquals(3, $d->icdf(0.956));
        $this->assertEquals(40, $d->icdf(0.9999999999999999));
    }
}
