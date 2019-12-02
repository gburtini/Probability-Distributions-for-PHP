<?php
/*
* Statistical Distributions for PHP - Gamma Distribution
*
* This gamma implementation requires that $alpha (aka shape) is greater than 0, rather than some old definitions that require it to be > -1.
*
* Copyright (C) 2015-2018 Giuseppe Burtini
*
* Other Credits
* -------------
* Interface and structure all (C) Giuseppe Burtini.
* Some work derived (with permission/license) from jStat - Javascript Statistical Library (MIT licensed).
* Some work derived (with permission/license) from Python Core (PSFL licensed).
* Some work, especially advisory, provided by Graeme Douglas.
*/

namespace gburtini\Distributions;

use gburtini\Distributions\Accessories\GammaFunction;
use gburtini\Distributions\Accessories\IncompleteGammaFunction;
use gburtini\Distributions\Interfaces\DistributionInterface;

/**
 * Class Gamma
 * https://en.wikipedia.org/wiki/Gamma_distribution
 * @package gburtini\Distributions
 */
class Gamma extends Distribution implements DistributionInterface
{
    protected $shape;
    protected $rate;

    /**
     * Gamma constructor.
     * @param $shape - α > 0
     * @param $rate - β > 0 - this is inverse of scale θ > 0 scale β = 1/θ
     *
     *
     *
     */
    public function __construct($shape, $rate)
    {
        $this->shape = floatval($shape); // alpha
        $this->rate = floatval($rate); // beta
    }
    public function rand()
    {
        return self::draw($this->shape, $this->rate);
    }
    // todo: refactor this code
    public static function draw($shape, $rate)
    {
        // This is a translation of Python Software Foundation licensed code from the Python project.

        $alpha = $shape;
        $beta = $rate;
        self::validateParameters($alpha, $beta);

        if ($alpha > 1) {
            // Uses R.C.H. Cheng, "The generation of Gamma variables with non-integral shape parameters", Applied Statistics, (1977), 26, No. 1, p71-74
            $ainv = sqrt(2.0 * $alpha - 1.0);
            $bbb = $alpha - log(4.0);
            $ccc = $alpha + $ainv;

            while (true) {
                $u1 = rand() / getrandmax();

                // todo: we should refactor this or type test that can reproduce these conditions
                if (!((1e-7 < $u1) && ($u1 < 0.9999999))) {
                    continue;
                }

                $u2 = 1.0 - (rand()/getrandmax());
                $v = log($u1 / (1.0-$u1))/$ainv;
                $x = $alpha * exp($v);
                $z = $u1 * $u1 * $u2;
                $r = $bbb+$ccc*$v-$x;
                $SG_MAGICCONST = 1 + log(4.5);
                if ($r + $SG_MAGICCONST - 4.5*$z >= 0.0 || $r >= log($z)) {
                    return $x * $beta;
                }
            }
        } elseif ($alpha == 1.0) {
            $u = rand()/getrandmax();
            while ($u <= 1e-7) {// todo: how to reproduce
                $u = rand()/getrandmax();
            }
            return -log($u) * $beta;
        } else { // 0 < alpha < 1
            // Uses ALGORITHM GS of Statistical Computing - Kennedy & Gentle
            do {
                $u3 = rand()/getrandmax();
                $b = (M_E + $alpha)/M_E;
                $p = $b*$u3;
                if ($p <= 1.0) {
                    $x = pow($p, (1.0/$alpha));
                } else {
                    $x = log(($b-$p)/$alpha);
                }
                $u4 = rand()/getrandmax();
                if ($p > 1.0) {
                    if ($u4 <= pow($x, ($alpha - 1.0))) {
                        break;
                    }
                } elseif ($u4 <= exp(-$x)) {
                    break;
                }
            } while (true);
            return $x * $beta;
        }
    }

    public static function validateParameters($a, $b)
    {
        $a = floatval($a);
        $b = floatval($b);

        if ($a <= 0 || $b <= 0) {
            throw new \InvalidArgumentException("Alpha and beta must be greater than 0.");
        }
    }

    public function pdf($x)
    {
        $a = $this->shape;
        $b = $this->rate;
        return exp($a * log($b) + ($a-1) * log($x) - $b * $x - GammaFunction::logGammaFunction($a));
    }

    /**
     * @param double $x
     * @return double
     */
    public function cdf($x)
    {
        if($x <= 0) { return (double) 0; }

        $a = $this->shape;
        $b = $this->rate;

        return 1 - IncompleteGammaFunction::complementedIncompleteGamma($a, $b * $x);
    }

    /**
     * @return double|double[]
     * http://mathworld.wolfram.com/Mean.html
     */
    public function mean()
    {
        $a = $this->shape;
        $t = 1 / $this->rate;
        return $a * $t;
    }

    /**
     * @return float|int
     * http://mathworld.wolfram.com/Variance.html
     */
    public function variance() {
        $a = $this->shape;
        $t = 1 / $this->rate;
        return $a * $t * $t;
    }

    /**
     * @return float|int
     * http://mathworld.wolfram.com/Skewness.html
     */
    public function skewness() {
        $a = $this->shape;
        return 2 / sqrt($a);
    }

    /**
     * @return float|int
     * http://mathworld.wolfram.com/Kurtosis.html
     *
     * This is controversial because of Both
     * https://en.wikipedia.org/wiki/Gamma_distribution
     * and
     * http://mathworld.wolfram.com/GammaDistribution.html
     * gives this equation but Mathematica gives
     * N[Kurtosis[GammaDistribution[4, 1]], 20]
     * 4.5000000000000000000
     *
     * Probably Mathematica has bug, this is connected with version 11.3
     */
    public function kurtosis() {
        $a = $this->shape;
        return 6 / $a;
    }
}
