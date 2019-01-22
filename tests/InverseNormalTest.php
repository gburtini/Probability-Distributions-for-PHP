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
}
