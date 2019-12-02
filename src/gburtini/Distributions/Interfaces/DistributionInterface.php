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

    /**
     * @return double|double[]
     */
    public function mean();
    /**
     * @return double|double[]
     */
    public function variance();
//    /**
//     * @return double|double[]
//     */
//    public function skewness();
//    /**
//       This is commented out because of problem with Mathematica a definion and MathWorld definition
//       if kurtosis is implemented then uses equations from MathWorld
//     * @return double|double[]
//     */
//    public function kurtosis();
}