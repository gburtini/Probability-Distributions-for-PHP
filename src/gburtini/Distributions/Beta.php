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

class Beta extends Distribution
{
    public $alpha;
    public $beta;

    // create a Beta(α, β) distribution
    public function __construct($a, $b)
    {
        self::validateParameters($a, $b);
            
        $this->alpha = $a;
        $this->beta = $b;
    }
        
    public function icdf($p)
    {
        $x = 0;
        $a = 0;
        $b = 1;
        $precision = $this->targetPrecision;
        $maxIterations = 100; // this can be changed and should probably be offered in a more configuration friendly way
        $currentIteration = 0;

        // limiting the currentIteration to 100 is a bit of a hack. I am not really sold.
        while ((($b - $a) > $precision) && ($currentIteration < $maxIterations)) {
            $x = ($a + $b) / 2;

            if (BetaFunction::incompleteBetaFunction($x, $this->alpha, $this->beta) > $p) {
                $b = $x;
            } else {
                $a = $x;
            }
            $currentIteration = $currentIteration + 1;
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
            
        return ($ag / ($ag+$bg));
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
