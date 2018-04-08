<?php
/*
* Probability Distributions for PHP - Weibull Distribution
*
* Copyright (C) 2017-2018 Giuseppe Burtini except where otherwise noted.
*
*/
namespace gburtini\Distributions;

use gburtini\Distributions\Accessories\GammaFunction;
use gburtini\Distributions\Accessories\BetaFunction;

class Weibull extends Distribution
{
    /**
     * Shape parameter
     * @var float
     */
    protected $k;
    /**
     * Scale parameter
     * @var float
     */
    protected $lambda;

    public function __construct($k, $lambda)
    {
        static::validateParameters($k, $lambda);

        $this->k = floatval($k);
        $this->lambda = floatval($lambda);
    }

    public static function validateParameters($k, $lambda)
    {
        if (!is_numeric($k) || !is_numeric($lambda)) {
            throw new \InvalidArgumentException("Non-numeric parameter in Weibull distribution (" . var_export($k, true) . ',' . var_export($lambda, true) . ").");
        }
        if ($k <= 0) {
            throw new \InvalidArgumentException("Parameter (\$k = " . var_export($k, true) . " must be positve. ");
        }
        if ($lambda <= 0) {
            throw new \InvalidArgumentException("Parameter (\$lambda = " . var_export($lambda, true) . " must be positve. ");
        }
    }

    public function mean()
    {
        return $this->lambda * exp(GammaFunction::logGammaFunction(1.0 + 1.0/$this->k));
    }

    public function variance()
    {
        return $this->lambda * $this->lambda * (
            exp(GammaFunction::logGammaFunction(1.0 + 2.0/$this->k))  -
            pow(exp(GammaFunction::logGammaFunction(1.0 + 1.0/$this->k)), 2)
        );
    }

    public function sd()
    {
        return sqrt($this->variance());
    }


    public function pdf($x)
    {
        if ($x < 0) {
            return 0.0;
        }

        $temp = $x / $this->lambda;

        return ($this->k / $this->lambda) * pow($temp, $this->k - 1) * exp(-pow($temp, $this->k));
    }

    public function cdf($x)
    {
        if ($x < 0) {
            return 0.0;
        }

        return 1 - exp(-pow($x / $this->lambda, $this->k));
    }

    public function icdf($p)
    {
        if ($p < 0 || $p > 1) {
            throw new \InvalidArgumentException("Parameter (\$p = " . var_export($p, true) . " must be between 0 and 1. ");
        }

        return $this->lambda * exp(log(-log(1 - $p)) / $this->k);
    }

    public function rand()
    {
        return self::draw($this->k, $this->lambda);
    }

    public static function draw($k, $lambda)
    {
        $x = mt_rand() / mt_getrandmax();
        return $lambda * exp(log(-log($x)) / $k);
    }
}
