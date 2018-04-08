<?php
use gburtini\Distributions\Normal;

class NormalDistributionTest extends PHPUnit_Framework_TestCase
{
    public function testNormalInstantiateDistribution()
    {
        $distribution1 = new Normal(0, 2.0);
        $distribution1 = new Normal(-3, 1.0);
    }

    public function testNormalInvalidInstantiation()
    {
        $this->setExpectedException('InvalidArgumentException');
        $invalid = new Normal(0, -2.0);
    }

    public function testNormalInvalidInstantiation2()
    {
        $this->setExpectedException('InvalidArgumentException');
        $invalid = new Normal(1, 0);
    }

    public function testObjectDraw()
    {
        mt_srand(1);

        $d = new Normal();

        $scale = 50000;
        $cutoff = 0.4;
        $counter = 0;

        $draws = new SplFixedArray($scale);
        for ($i = 0; $i < $scale; $i++) {
            $x = $d->rand();
            $draws[$i] = $x;

            if ($x > $cutoff) {
                $counter = $counter + 1;
            }
        }

        $number = array_sum((array) $draws) / count($draws);
        $this->assertEquals(0, $number, "Attempting to draw from N(0,1) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);

        $p = $counter / $scale;
        $this->assertEquals(1-$d->cdf($cutoff), $p, "Attempting to draw from N(0,1) {$scale} times gives the wrong number of values greater than {$cutoff}. This could be just random chance.", 0.01);
    }

    public function testClassDraw()
    {
        mt_srand(1);

        $scale = 50000;
        $draws = new SplFixedArray($scale);
        for ($i = 0; $i < $scale; $i++) {
            $draws[$i] = Normal::draw(0, 1);
        }

        $number = array_sum((array) $draws) / count($draws);
        $this->assertEquals(0, $number, "Attempting to draw from N(0,1) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);
    }

    public function testNormalPDF()
    {
        $d = new Normal(0, 1);

        $this->assertEquals($d->pdf(-5), 0.000001486719514, "PDF incorrect", 1e-9);
        $this->assertEquals($d->pdf(-2.5), 0.01752830049, "PDF incorrect", 1e-9);
        $this->assertEquals($d->pdf(-0.5), 0.3520653267, "PDF incorrect", 1e-9);
        $this->assertEquals($d->pdf(1.5), 0.1295175956, "PDF incorrect", 1e-9);
        $this->assertEquals($d->pdf(3.5), 0.0008726826947, "PDF incorrect", 1e-9);
    }

    public function testNormalCDF()
    {
        $d = new Normal(0, 1);

        $this->assertEquals($d->cdf(-3.5), 0.000232629079035525, "CDF incorrect", 1e-7);
        $this->assertEquals($d->cdf(-1.5), 0.0668072012688581, "CDF incorrect", 1e-7);
        $this->assertEquals($d->cdf(-0.5), 0.308537538725987, "CDF incorrect", 1e-7);
        $this->assertEquals($d->cdf(0.5), 0.691462461274013, "CDF incorrect", 1e-7);
        $this->assertEquals($d->cdf(1.5), 0.933192798731142, "CDF incorrect", 1e-7);
        $this->assertEquals($d->cdf(3.5), 0.999767370920964, "CDF incorrect", 1e-7);
        $this->assertEquals($d->cdf(5.0), 0.99999971346, "CDF incorrect", 1e-7);
    }

    public function testNormalICDF()
    {
        $d = new Normal(0, 1);

        $this->assertEquals($d->icdf(0.2), -0.841621233572216, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.4), -0.253347103135800, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.7), 0.524400512708049, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.9), 1.28155156554473, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.95), 1.64485362695213, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.975), 1.95996398453944, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.99), 2.32634787404074, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.9999), 3.71901648545454, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.999999), 4.75342430881908, "Inverse CDF incorrect", 1e-7);
    }

    public function testNormalICDFWithNonzeroMean()
    {
        $mu = 0.78135;
        $d = new Normal($mu, 1);

        $this->assertEquals($d->icdf(0.2), $mu -0.841621233572216, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.4), $mu -0.253347103135800, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.7), $mu + 0.524400512708049, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.9), $mu + 1.28155156554473, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.95), $mu + 1.64485362695213, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.975), $mu + 1.95996398453944, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.99), $mu + 2.32634787404074, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.9999), $mu + 3.71901648545454, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.999999), $mu + 4.75342430881908, "Inverse CDF incorrect", 1e-7);
    }

    public function testNormalICDFWithOtherVariance()
    {
        $mu = 1;
        $sigma = 0.7;
        $d = new Normal($mu, $sigma*$sigma);

        $this->assertEquals($d->icdf(0.2), $mu - $sigma * 0.841621233572216, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.4), $mu - $sigma * 0.253347103135800, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.7), $mu + $sigma * 0.524400512708049, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.9), $mu + $sigma * 1.28155156554473, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.95), $mu + $sigma * 1.64485362695213, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.975), $mu + $sigma * 1.95996398453944, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.99), $mu + $sigma * 2.32634787404074, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.9999), $mu + $sigma * 3.71901648545454, "Inverse CDF incorrect", 1e-7);
        $this->assertEquals($d->icdf(0.999999), $mu + $sigma * 4.75342430881908, "Inverse CDF incorrect", 1e-7);
    }
}
