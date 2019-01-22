<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 22.01.19
 * Time: 21:25
 */

use gburtini\Distributions\Dirichlet;

class DirichletTest extends PHPUnit_Framework_TestCase
{
    public function testPdfInvalidArg() {
        $d = new Dirichlet([1,2,3]);
        $this->setExpectedException('InvalidArgumentException');
        $invalid = $d->pdf([2]);
    }

    public function testInvalidConstructor() {
        $x = (int) "1";
        $this->setExpectedException('InvalidArgumentException');
        $d = new Dirichlet($x);
    }

    public function testConcentrationConstructor() {
        $d = new Dirichlet(0.3, 3);
        $this->assertEquals(3, $d->getDimension());
    }

    public function testPdf() {
        $d = new Dirichlet([1,2,3]);
        $x = [.5,.3];

        $this->assertEquals(0.72000000000000000000,$d->pdf($x));
        $this->assertEquals(0, $d->pdf([.5,.6]));
        $this->assertCount(2, $x); // array_push should no change length of argument
        $this->assertInternalType('double', $d->pdf([.5,.6]));

    }

    public function testDraw() {
        $this->assertCount(4,Dirichlet::draw([1,2,3,4]));
    }

    public function testRand() {
        $this->assertInternalType('array', (new Dirichlet([2]))->rand());
    }
}