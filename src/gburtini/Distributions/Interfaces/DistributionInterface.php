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
    /**
     * @param double|double[] $x
     * @return double|double[]
     */
    public function pdf($x);

    /**
     * @param double $x
     * @return double
     */
    public function cdf($x);
}