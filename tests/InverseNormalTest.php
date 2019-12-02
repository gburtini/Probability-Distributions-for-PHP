<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 22.01.19
 * Time: 21:05
 */

use gburtini\Distributions\InverseNormal;

class InverseNormalTest extends PHPUnit_Framework_TestCase
{
    public function testPdf() {
        $d = new InverseNormal(3,5);
        $this->assertEquals($d->pdf(6),0.040014017550445998882);
    }
    public function testRandAndDraw() {
        $this->assertInternalType('double', (new InverseNormal(4,1/5))->rand());
        $this->assertInternalType('double', InverseNormal::draw(4,1/5));
    }

    public function testCDF() {
        $d = new InverseNormal(3,5);
        $this->assertEquals(0.41692560139409508138, $d->cdf(2), "Invalid value of CDF", 1e-7);
    }
    public function testMeanVarianceSkewnessKurtosis() {
        $d = new InverseNormal(3,4);
        $this->assertEquals(3.0000000000000000000, $d->mean());
        $this->assertEquals(6.7500000000000000000, $d->variance());
        $this->assertEquals(2.5980762113533159403, $d->skewness());
        $this->assertEquals(11.25, $d->kurtosis());
    }
}
