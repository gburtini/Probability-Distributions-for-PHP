<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 23.01.19
 * Time: 01:47
 */

namespace gburtini\Distributions;


class BernoulliTest extends \PHPUnit_Framework_TestCase
{

    public function testIcdf()
    {
        $d = new Bernoulli(.7);
        $this->assertEquals(0,$d->icdf(.3));
        $this->assertEquals(1,$d->icdf(.8));
    }
//
//    public function test__construct()
//    {
//
//    }

    public function testSd()
    {
        $d = new Bernoulli(.7);
        $this->assertEquals(0.45825756949558400066,$d->sd());
    }

    public function testRandAndDraw()
    {
        $this->assertInternalType('int', (new Bernoulli(.2))->rand());
        $this->assertInternalType('int', Bernoulli::draw(.2));
    }

    public function testPdf()
    {
        $d = new Bernoulli(.3);
        $this->assertEquals(.3,$d->pdf(1));
        $this->assertEquals(.7,$d->pdf(0));
        $this->assertEquals(0,$d->pdf(0.5));
    }

    public function testCdf()
    {
        $d = new Bernoulli(.4);
        $this->assertEquals(0,$d->cdf(-.4));
        $this->assertEquals(.6,$d->cdf(.4));
        $this->assertEquals(1,$d->cdf(1.4));
    }

    public function testMean()
    {
        $d = new Bernoulli(.7);
        $this->assertEquals(.7,$d->mean());
    }

    public function testVarianceSkewness()
    {
        $d = new Bernoulli(.4);
        $this->assertEquals(.24,$d->variance());
        $this->assertEquals(0.40824829046386301637,$d->skewness());
        $this->assertEquals(1.1666666666666666667,$d->kurtosis());
    }



    public function testValidateParameters()
    {
        $this->setExpectedException('InvalidArgumentException');
        Bernoulli::validateParameters(-1);
    }

    public function testValidateParametersInICDF()
    {
        $this->setExpectedException('InvalidArgumentException');
        (new Bernoulli(.7))->icdf(-1.3);
    }
}
