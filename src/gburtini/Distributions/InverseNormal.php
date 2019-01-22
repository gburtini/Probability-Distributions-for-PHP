<?php
/*
* Inverse Normal Distribution - Statistical Distributions for PHP
* see: https://en.wikipedia.org/wiki/Inverse_Gaussian_distribution#Generating_random_variates_from_an_inverse-Gaussian_distribution
*/
namespace gburtini\Distributions;

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

    public static function draw($mu, $lambda)
    {
        $norm = new Normal(0, 1);
        $v = $norm->draw(0, 1);
        $y = pow($v, 2);
        $x = $mu + (pow($mu, 2) * $y) / (2 * $lambda) - ($mu / (2 * $lambda)) * sqrt((4 * $mu * $lambda * $y) + (pow($mu, 2) * pow($y, 2)));
        $z = mt_rand() / mt_getrandmax();
        if ($z <= ($mu / ($mu + $x))) {
            return $x;
        } else {
            return (pow($mu, 2) / $x);
        }
    }

    public function pdf($x)
    {
        return sqrt($this->lambda / ( 2 * M_PI * pow($x,3)) )
            * exp(-$this->lambda * pow($x - $this->mu,2) / (2 * $x * pow($this->mu,2)) );
    }
}
