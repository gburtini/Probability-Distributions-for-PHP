<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 22.01.19
 * Time: 23:58
 */

namespace gburtini\Distributions\Accessories;


class ErfFunctionTest extends \PHPUnit_Framework_TestCase
{
    public function testErf() {
        $this->assertEquals(-1, ErfFunction::val(-INF));
        $this->assertEquals(0, ErfFunction::val(0),"Invalid erf value", 1e-7);
        $this->assertEquals(0.84270079294971486934, ErfFunction::val(1),"Invalid erf value", 1e-7);
    }
}
