<?php
/*
* Statistical Distributions for PHP - Dirchlet Distribution
*
* Copyright (C) 2015-2018 Giuseppe Burtini.
*/
namespace gburtini\Distributions;

use gburtini\Distributions\Accessories\GammaFunction;
use gburtini\Distributions\Interfaces\DistributionInterface;
use phpDocumentor\Reflection\Types\Array_;

class Dirichlet extends Distribution implements DistributionInterface
{
    protected $k; // this variable is not documented, should be depreciated
    protected $alpha;
    protected $gammaDistributions;
    public function __construct($concentration, $number = null)
    {
        if (!is_array($concentration)) { // meaning of number should be documented
            if ($number !== null) { // add test to this behavior
                $concentration = array_fill_keys(range(0, $number-1), $concentration);
            } else {
                throw new \InvalidArgumentException("Concentration wasn't a valid array and number not specified. Creating a dirichlet is impossible.");
            }
        }

        $this->alpha = $concentration;
    }

    public function getDimension() {
        return count($this->alpha);
    }

    public function rand()
    {
        return self::draw($this->alpha);
    }

    /**
     * @param array $alpha
     * @param null $k
     * @return array
     */
    public static function draw(array $alpha, $k = null) // k is not used in this function, why?
    {
        $draws = array();
        foreach ($alpha as $g) {
            $draws[] = Gamma::draw($g, 1);
        }

        $sum = array_sum($draws);

        return array_map(
            function ($draws) use ($sum) {
                return $draws / $sum;
            },
            $draws,
            array_fill(0, count($draws), $sum)
        );
    }

    /**
     * @param double[] $x
     * @return double
     */
    public function pdf($x)
    {
        if(!is_array($x) || count($x) !== count($this->alpha) - 1) {
            throw new \InvalidArgumentException("We considering symmetric DirichletDistribution, so you should paste K-1 arguments there, where K = count(alpha)");
        }

        // calculating helping variables
        $K = count($this->alpha); // dimension
        $sumX = array_sum($x); // sum of x
        $sumA = array_sum($this->alpha); // sum of alpha

        // extending $x for simplify loops in future calculations
        array_push($x, 1-$sumX);

        // condition to be 0
        for($i = 0; $i < $K; $i++) {
            if($x[$i] < 0) { return (double) 0; }
        }

        // calculating value
        $exponent = GammaFunction::logGammaFunction($sumA);

        for($i = 0; $i < $K; $i++) {
            $exponent += ( ( $this->alpha[$i] - 1 ) * log($x[$i])) - GammaFunction::logGammaFunction($this->alpha[$i]);
        }

        return exp($exponent);
    }

    /**
     * @param double|double[] $x
     * @throws \Exception
     */
    public function cdf($x)
    {
        throw new \Exception("NOT IMPLEMENTED BECAUSE OF NOT DEFINED");
    }

    /**
     * @return double|double[]
     */
    public function mean()
    {
        $sum = array_sum($this->alpha);
        $alphaCopy = $this->alpha; // clone to avoid destruction alpha by reference in next method
        array_splice($alphaCopy, -1);
        return array_map(function ($a) use ($sum) {return $a/$sum; }, $alphaCopy);
    }

    /**
     * @return double|double[]
     */
    public function variance()
    {
        throw new \Exception("NOT_IMPLEMENTED");
        // TODO: Implement variance() method.
    }
//
//    /**
//     * @return double|double[]
//     */
//    public function skewness()
//    {
//        // TODO: Implement skewness() method.
//    }
//
//    /**
//     * @return double|double[]
//     */
//    public function kurtosis()
//    {
//        // TODO: Implement kurtosis() method.
//    }
}
