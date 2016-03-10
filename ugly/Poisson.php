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
 * $poisson = new Poissin($lambda>0);
 * $poisson->pdf($x) = [0,1]
 * $poisson->cdf($x) = [0,1] non-decreasing
 * $poisson::quantile($y in [0,1]) = [0,1] (aliased Poisson::icdf)
 * $poisson->rand() = [0,1]
 *
 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>.
 *
 * Other Credits
 * -------------
 * Interface and structure all (C) Giuseppe Burtini.
 * Some work derived (with permission/license) from jStat - Javascript Statistical Library (MIT licensed).
 * Some work derived (with permission/license) from Python Core (PSL licensed).
 * Some work, especially advice, provided by Graeme Douglas.
 */
 	require_once dirname(__FILE__) . "/Distribution.php";

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

		/** Could be made more efficient with an implementation of the incomplete Gamma function **/
		public function cdf($k)
		{
			$accumuluated = 0.0;

			for ($i=0; $i<=$k; $i++) {
				$accumuluated += $this->pdf($i);
			}
			return $accumuluated;
		}

		/** Again, not a very efficient implementation */
		public function icdf($p)
		{
			if ($p < 0 || $p > 1) {
				throw new InvalidArgumentException("Parameter (\$p = " . var_export($p, true) . " must be between 0 and 1. ");
			}

			if ($p == 1) return INF;


			$accumuluated = 0.0;
			$k = 0;

			do {
				$delta = $this->pdf($k);
				$accumuluated = $accumuluated + $delta;

				if ($accumuluated >= $p) return $k;

				$k = $k + 1;
			} while($delta > 1e-9);

			return $k;
		}
	}
