<?php
/*
* Probability Distributions for PHP - Binomial Distribution
*
* Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>.
*
* Other credits
* Original implementation by Frank Wikström.
*/
require_once dirname(__FILE__) . "/Distribution.php";
require_once dirname(__FILE__) . "/Accessories/GammaFunction.php";
require_once dirname(__FILE__) . "/Beta.php";

class GBPDP_Binomial extends GBPDP_Distribution {
	public $n;
	public $p;

	public function __construct($n, $p) {
		self::validateParameters($n, $p);

		$this->n = $n;
		$this->p = $p;
	}

	public function mean() { return $this->n * $this->p; }
	public function variance() { return $this->n * $this->p * (1-$this->p);}
	public function sd() { return sqrt($this->variance()); }
	public function rand() {
		return self::draw($this->n, $this->p);
	}

	/** O(n) method of generating Binom(n,p) distributed random numbers */
	public static function draw($n, $p) {

		$x = 0;

		for ($i = 0; $i < $n; $i++)
		if((mt_rand()/mt_getrandmax()) < $p)
		$x = $x + 1;

		return $x;
	}

	public static function validateParameters($n, $p) {

		if (!is_int($n) || $n <= 0) {
			throw new InvalidArgumentException("Parameter (\$n = " . var_export($n, true) . " must be a positive integer. ");
		}

		if($p < 0 || $p > 1) {
			throw new InvalidArgumentException("Parameter (\$p = " . var_export($p, true) . " must be between 0 and 1. ");
		}
	}

	public function pdf($k)
	{
		$logBinom = GBPDP_GammaFunction::logGammaFunction($this->n + 1) - GBPDP_GammaFunction::logGammaFunction($k + 1) - GBPDP_GammaFunction::logGammaFunction($this->n - $k + 1);
		$logP = $logBinom + $k * log($this->p) + ($this->n - $k)*log(1-$this->p);

		return exp($logP);
	}

	/* Could be improved with the implementation of the incomplete beta funciton */
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
		} while($k < $this->n);
		return $k;
	}
}


class GBPDP_Binomial_CI extends GBPDP_Binomial {
	public function jeffreys($confidence=0.95) {
		// this is a Bayesian derivation with great frequentist properties, even around 0 and 1 success rates.

		if($confidence > 1 || $confidence < 0) {
			throw new InvalidArgumentException("p-value must be between 0 and 1.");
		}

		$a = ($this->n * $this->p);
		$b = ($this->n * (1-$this->p));
		$beta = new GBPDP_Beta(0.5 + $a, 0.5 + $b);

		if($a > 0) {
			$lower = $beta->icdf(1-($confidence));
		} else {
			$lower = 0;
		}

		if($a < $this->n) {
			$upper = $beta->icdf(($confidence));
		} else {
			$upper = 1;
		}

		return array(
			"lower" => $lower, 
			"upper" => $upper
		);
	}
}

