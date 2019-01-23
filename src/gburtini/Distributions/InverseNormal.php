<?php
/*
* Inverse Normal Distribution - Statistical Distributions for PHP
* see: https://en.wikipedia.org/wiki/Inverse_Gaussian_distribution#Generating_random_variates_from_an_inverse-Gaussian_distribution
*/
namespace gburtini\Distributions;

use gburtini\Distributions\Accessories\ErfFunction;
use gburtini\Distributions\Interfaces\DistributionInterface;

require_once dirname(__FILE__) . "/Normal.php";
require_once dirname(__FILE__) . "/Distribution.php";

/**
 * Class InverseNormal
 * @package gburtini\Distributions
 *
 * https://en.wikipedia.org/wiki/Normal-inverse_Gaussian_distribution
 * http://mathworld.wolfram.com/InverseGaussianDistribution.html
 */
class InverseNormal extends Distribution implements DistributionInterface
{
    private $mu;
    private $lambda;

    public function __construct($mean, $scale) // mu, lambda
    {
        $this->mu = $mean;
        $this->lambda = $scale;
    }

    public function rand() {
        return self::draw($this->mu, $this->lambda);
    }

    public static function draw($mu, $lambda)
    {
        $v = Normal::draw(0, 1);
        $y = pow($v, 2);
        $x = $mu + (pow($mu, 2) * $y) / (2 * $lambda) - ($mu / (2 * $lambda)) * sqrt((4 * $mu * $lambda * $y) + (pow($mu, 2) * pow($y, 2)));
        $z = mt_rand() / mt_getrandmax();
        return ($z <= ($mu / ($mu + $x))) ? $x : (pow($mu, 2) / $x);
    }

    public function pdf($x)
    {
        return sqrt($this->lambda / ( 2 * M_PI * pow($x,3)) )
            * exp(-$this->lambda * pow($x - $this->mu,2) / (2 * $x * pow($this->mu,2)) );
    }

    /**
     * @param double $x
     * @return double
     * http://mathworld.wolfram.com/InverseGaussianDistribution.html
     */
    public function cdf($x)
    {
        $s = sqrt($this->lambda / (2*$x));
        $erfM = ErfFunction::val($s * ($x/$this->mu - 1));
        $erfP = ErfFunction::val($s * ($x/$this->mu + 1));

        return 0.5 * (1 + $erfM)
            + 0.5 * exp(2*$this->lambda / $this->mu) * (1 - $erfP);
    }

    /**
     * @return double|double[]
     */
    public function mean()
    {
        return $this->mu;
    }

    /**
     * @return double|double[]
     */
    public function variance()
    {
        return pow($this->mu,3) / $this->lambda;
    }

    /**
     * @return double|double[]
     */
    public function skewness()
    {
        return 3 * sqrt($this->mu / $this->lambda );
    }

    /**
     * @return double|double[]
     *
     * Warning. Controversial values during test!
     *
     * Mathematica 11.3 gives 14.25 but equation form MathWorld 11.25
     *
     * 14.25 <- mathematica
     * 11.25 <- this program
     *
     * N[(#[InverseGaussianDistribution[3, 4]]) & /@ {Mean, Variance, Skewness, Kurtosis}, 20]
     *
     * http://mathworld.wolfram.com/InverseGaussianDistribution.html
     */
    public function kurtosis()
    {
        return 15 * $this->mu / $this->lambda;
    }
}
