<?php
/*
* Probability Distributions for PHP - Binomial Distribution
*
* Copyright (C) 2015-2018 Giuseppe Burtini.
*
* Other credits
* Original implementation by Frank Wikström.
*/
namespace gburtini\Distributions;

use gburtini\Distributions\Accessories\GammaFunction;

class Binomial extends Distribution
{
    public $n;
    public $p;

    public function __construct($n, $p)
    {
        self::validateParameters($n, $p);

        $this->n = $n;
        $this->p = $p;
    }

    public function mean()
    {
        return $this->n * $this->p;
    }
    public function variance()
    {
        return $this->n * $this->p * (1-$this->p);
    }
    public function sd()
    {
        return sqrt($this->variance());
    }
    public function rand()
    {
        return self::draw($this->n, $this->p);
    }

    /** O(n) method of generating Binom(n,p) distributed random numbers */
    public static function draw($n, $p)
    {
        $x = 0;

        for ($i = 0; $i < $n; $i++) {
            if ((mt_rand()/mt_getrandmax()) < $p) {
                $x = $x + 1;
            }
        }

        return $x;
    }

    public static function validateParameters($n, $p)
    {
        if (!is_int($n) || $n <= 0) {
            throw new \InvalidArgumentException("Parameter (\$n = " . var_export($n, true) . " must be a positive integer. ");
        }

        if ($p < 0 || $p > 1) {
            throw new \InvalidArgumentException("Parameter (\$p = " . var_export($p, true) . " must be between 0 and 1. ");
        }
    }

    public function pdf($k)
    {
        $logBinom = GammaFunction::logGammaFunction($this->n + 1) - GammaFunction::logGammaFunction($k + 1) - GammaFunction::logGammaFunction($this->n - $k + 1);
        $logP = $logBinom + $k * log($this->p) + ($this->n - $k)*log(1-$this->p);

        return exp($logP);
    }

    /* Could be improved with the implementation of the incomplete beta funciton */
    public function cdf($k)
    {
        $accumulator = 0.0;

        for ($i=0; $i<=$k; $i++) {
            $accumulator += $this->pdf($i);
        }
        return $accumulator;
    }

    /** Again, not a very efficient implementation */
    public function icdf($p)
    {
        if ($p < 0 || $p > 1) {
            throw new \InvalidArgumentException("Parameter (\$p = " . var_export($p, true) . " must be between 0 and 1. ");
        }
        if ($p == 1) {
            return INF;
        }
        $accumulator = 0.0;
        $k = 0;
        do {
            $delta = $this->pdf($k);
            $accumulator = $accumulator + $delta;
            if ($accumulator >= $p) {
                return $k;
            }
            $k = $k + 1;
        } while ($k < $this->n);
        return $k;
    }
}
