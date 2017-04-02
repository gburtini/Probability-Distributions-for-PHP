<?php
/*
 * Probability Distributions for PHP - Poisson Distribution
 *
 * This is an implementation of the Poisson distribution.
 *
 * Use either as an instance variable or statically.
 *
 * use gburtini\Distributions\Poisson;
 *
 * $poisson = new Poisson($lambda>0);
 * $poisson->pdf($x) = [0,1]
 * $poisson->cdf($x) = [0,1] non-decreasing
 * $poisson::quantile($y in [0,1]) = [0,1] (aliased Poisson::icdf)
 * $poisson->rand() = [0,1]
 *
 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>.
 *
 * Other Credits
 * -------------
 * Implementation by Frank Wikström.
 */
 	require_once dirname(__FILE__) . "/Distribution.php";
    require_once dirname(__FILE__) . "/Accessories/GammaFunction.php";
    require_once dirname(__FILE__) . "/Accessories/IncompleteGammaFunction.php";

	class GBPDP_Poisson extends GBPDP_Distribution {
		public $lambda;

		public function __construct($lambda) {
			self::validateParameters($lambda);

			$this->lambda = $lambda;
		}

		public function mean() { return $this->lambda; }
		public function variance() { return $this->lambda;}
		public function sd() { return sqrt($this->variance()); }
		public function rand() {
			return self::draw($this->lambda);
		}

		/*
		 * Generate Poisson distrubuted random numbers, using the Knuth-Junhao
		 * algorithm.
	  	 */
		public static function draw($lambda) {

			$STEP = 100;

			$lambda_left = $lambda;
			$k = 0;
			$p = 1.0;

			do {
				$k = $k + 1;
				$u = mt_rand()/mt_getrandmax();
				$p = $p * $u;

				if ($p < M_E && $lambda_left > 0) {
					if ($lambda_left > $STEP) {
						$p = $p * exp($STEP);
						$lambda_left = $lambda_left - $STEP;
					} else {
						$p = $p * exp($lambda_left);
						$lambda_left = -1;
					}
				}
			} while($p > 1);

			return $k-1;
		}

		public static function validateParameters($lambda) {
			$lambda = floatval($lambda);

			if($lambda <= 0) {
				throw new InvalidArgumentException("Parameter (\$lambda = " . var_export($lambda, true) . " must be greater than 0. ");
			}
		}

		public function pdf($k)
		{
			$logP = $k*log($this->lambda) - $this->lambda - GBPDP_GammaFunction::logGammaFunction($k+1);

			return exp($logP);
		}

		public function cdf($k)
		{
            $k = floor($k);
            return GBPDP_IncompleteGammaFunction::ComplementedIncompleteGamma($k+1, $this->lambda);
		}


        public function icdf($p)
        {
            if ($p < 0 || $p > 1) {
                throw new InvalidArgumentException("Parameter (\$p = " . var_export($p, true) . " must be between 0 and 1. ");
            }

            if ($p == 0) return 0;
            if ($p == 1) return INF;

            $lambda = $this->lambda;

            $w = GBPDP_IncompleteGammaFunction::InverseNormal($p);
            $w2 = $w*$w;
            $w4 = $w2*$w2;

            $Q1 = $lambda + $w * sqrt($lambda) + (2.0 + $w*$w)/6.0;
            $Q2 = $Q1 - $w * (2.0 - $w2)/(72.0*sqrt($lambda));

            $error = (4.0 + 2.0*$w2 + $w4)/(160.0 * $lambda);

            $k0 = floor($Q2 - $error);
            if ($k0 < 0) $k0 = 0;

            // The error bound isn't completely reliable. Double check the result
            // unfortunately, this will slow down the code by roughly a factor 2,
            // but the implementation is still about twice as fast as the naive one

            $check = $this->cdf($k0);
            while ($check >= $p) {
                $dp = $this->pdf($k0);
                if ($check - $dp < $p) return $k0;
                $check = $check - $dp;
                $k0 = $k0 - 1;
                if ($k0 <= 0) return 0;
            }

            while ($check < $p) {
                $dp = $this->pdf($k0+1);
                $check = $check + $dp;
                $k0 = $k0 + 1;
            }
            return $k0;
        }

        // Returns the value of $lambda, such that Poisson($lambda)->cdf($k) == $p
        public static function lambda($k, $p)
        {
            return GBPDP_IncompleteGammaFunction::InverseComplementedIncompleteGamma($k+1, $p);
        }

	}
