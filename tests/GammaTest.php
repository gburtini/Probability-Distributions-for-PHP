<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 22.01.19
 * Time: 20:26
 */

use gburtini\Distributions\Gamma;

class GammaTest extends PHPUnit_Framework_TestCase
{
    public function testPdf() {
        $d = new Gamma(2,1/3);
        $this->assertEquals($d->pdf(0.5),0.047026762493923004114);
    }
    public function testRandAndDraw() {
        $this->assertInternalType('double', (new Gamma(4,1/5))->rand());
        $this->assertInternalType('double', Gamma::draw(4,1/5));
    }
    public function testValidate() {
        $this->setExpectedException('InvalidArgumentException');
        Gamma::validateParameters(-1, 0);
    }
}