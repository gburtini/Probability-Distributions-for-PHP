<?php
/*
    * Probability Distributions for PHP - Beta Distribution
    *
    * This is an implementation of the beta distribution of the first kind, that is, the conjugate prior
    * for the Bernoulli binomial and geometric distributions. It is defined solely on the interval [0,1]
    * and parameterized on α>0, β>0.
    *
    * Use either as an instance variable or statically.
    *
    * use gburtini\Distributions\Beta;
    *
    * $beta = new Beta($alpha>0, $beta>0);
    * $beta->pdf($x) = [0,1]
    * $beta->cdf($x) = [0,1] non-decreasing
    * $beta::quantile($y in [0,1]) = [0,1] (aliased Beta::icdf)
    * $beta->rand() = [0,1]
    *
    * Copyright (C) 2015-2018 Giuseppe Burtini
    *
    * Other Credits
    * -------------
    * Interface and structure all (C) Giuseppe Burtini.
    * Some work derived (with permission/license) from jStat - Javascript Statistical Library (MIT licensed).
    * Some work derived (with permission/license) from Python Core (PSL licensed).
    * Some work, especially advice, provided by Graeme Douglas.
    */


namespace gburtini\Distributions;

use gburtini\Distributions\Accessories\GammaFunction;
use gburtini\Distributions\Accessories\BetaFunction;
use gburtini\Distributions\Interfaces\DistributionInterface;

class Beta extends Distribution implements DistributionInterface
{
    public $alpha;
    public $beta;
    /**
     * @var { \mathrm {B} (\alpha ,\beta ) = {\frac {\Gamma (\alpha )\Gamma (\beta )}{\Gamma (\alpha +\beta )}}}
     * value used to calculate PDF
     * https://en.wikipedia.org/wiki/Beta_distribution
     * {\mathrm {B} } is a normalization constant to ensure that the total probability is 1
     */
    public $logB; // logarithm of normalization constant that can be huge number

    // create a Beta(α, β) distribution
    public function __construct($a, $b)
    {
        self::validateParameters($a, $b);

        $this->alpha = $a;
        $this->beta = $b;
        $this->logB = GammaFunction::logGammaFunction($a) + GammaFunction::logGammaFunction($b) - GammaFunction::logGammaFunction($a+$b);
    }

    public function pdf($x)
    {
        $exponent = ($this->alpha-1) * log($x) + ($this->beta-1) * log(1-$x) - $this->logB;
        return exp($exponent);
    }

    /**
     * @param $x
     * @return float|int
     * Definition: https://en.wikipedia.org/wiki/Beta_function#Incomplete_beta_function
     * we can calculate integral but better idea is to use fraction representation:
     * In our code there is implemented method http://functions.wolfram.com/GammaBetaErf/Beta3/10/
     */
    public function cdf($x)
    {
        return BetaFunction::incompleteBetaFunction($x, $this->alpha, $this->beta);
    }

    public function icdf($p, $params = ["maxIterations" => 100])
    {
        $x = 0;
        $a = 0;
        $b = 1;
        $precision = $this->targetPrecision;
        $maxIterations = $params["maxIterations"];
        $currentIteration = 0;

        // limiting the currentIteration to 100 is a bit of a hack. I am not really sold.
        while ((($b - $a) > $precision) && ($currentIteration++ < $maxIterations)) {
            $x = ($a + $b) / 2;

            if ($this->cdf($x) > $p) {
                $b = $x;
            } else {
                $a = $x;
            }
        }

        return $x;
    }

    public function rand()
    {
        return self::draw($this->alpha, $this->beta);
    }

    public static function draw($a, $b)
    {
        $ag = Gamma::draw($a, 1);
        $bg = Gamma::draw($b, 1);

        return ($ag / ($ag + $bg));
    }

    public static function validateParameters($a, $b)
    {
        $a = floatval($a);
        $b = floatval($b);

        if ($a <= 0 || $b <= 0) {
            throw new \InvalidArgumentException("α (\$a = " . var_export($a, true) . "), β (\$b = " . var_export($b, true) . ") must each be greater than 0. ");
        }
    }
}
