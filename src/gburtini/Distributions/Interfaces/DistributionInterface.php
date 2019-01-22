<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 22.01.19
 * Time: 20:08
 */

namespace gburtini\Distributions\Interfaces;

interface DistributionInterface
{
    public function pdf($x);
}