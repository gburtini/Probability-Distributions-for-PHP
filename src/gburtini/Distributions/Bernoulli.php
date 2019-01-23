<?php
/*
    * Probability Distributions for PHP - Binomial Distribution
    *
    * Copyright (C) 2015-2018 Giuseppe Burtini.
    */
namespace gburtini\Distributions;


use gburtini\Distributions\Interfaces\DistributionInterface;

class Bernoulli extends Distribution implements DistributionInterface
{
    public $fraction; // this should be private because of we do not want
    // to allow for modification of this parameter because of validation
    // is in constructor
    private $p;
    private $q;

    public function __construct($fraction)
    {
        self::validateParameters($fraction);

        $this->fraction = $fraction;
        $this->p = $fraction;
        $this->q = (1-$fraction);
    }

    public function mean()
    {
        return $this->fraction;
    }
    public function variance()
    {
        return ($this->fraction) * (1 - $this->fraction);
    }
    public function sd()
    {
        return sqrt($this->variance());
    }
    public function rand()
    {
        return self::draw($this->fraction);
    }

    public static function draw($fraction)
    {
        return (int) ( (mt_rand()/mt_getrandmax()) > $fraction);
    }

    public static function validateParameters($fraction)
    {
        $fraction = floatval($fraction);

        if ($fraction < 0 || $fraction > 1) {
            throw new \InvalidArgumentException("Fraction (\$fraction = " . var_export($fraction, true) . " must be between 0 and 1. ");
        }
    }

    public function pdf($k)
    {
        if ($k == 0) {
            return 1 - $this->fraction;
        }
        if ($k == 1) {
            return $this->fraction;
        }

        return 0.0;
    }

    public function cdf($k)
    {
        if ($k < 0) {
            return 0.0;
        }
        if ($k < 1) {
            return 1 - $this->fraction;
        }
        return 1.0;
    }

    public function icdf($p)
    {
        if ($p < 0 || $p > 1) {
            throw new \InvalidArgumentException("Parameter (\$p = " . var_export($p, true) . " must be between 0 and 1. ");
        }

        if ($p <= 1-$this->fraction) {
            return 0;
        }
        return 1;
    }

    /**
     * @return double|double[]
     */
    public function skewness()
    {
        return (1-2 * $this->fraction) / sqrt($this->p * $this->q);
    }

    /**
     * @return double|double[]
     */
    public function kurtosis()
    {
        // this ie ex curtosis from wiki
        //return // (1 - 6 * $this->p * $this->q) / ( $this->p * $this->q );

        $p = $this->p;
        return 3 + (1 - 6 * (1 - $p) * $p)/((1 - $p) * $p);
    }
}
