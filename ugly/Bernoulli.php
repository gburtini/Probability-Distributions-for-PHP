<?php
	/*
	 * Probability Distributions for PHP - Binomial Distribution
	 *
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>.
	 */
	require_once dirname(__FILE__) . "/Distribution.php";

	class GBPDP_Bernoulli extends GBPDP_Distribution {
		public $fraction;

		public function __construct($fraction) {
			self::validateParameters($fraction);

			$this->fraction = $fraction;
		}

		public function mean() { return $this->fraction; }
		public function variance() { return ($this->fraction) * (1-$this->fraction);}
		public function sd() { return sqrt($this->variance()); }
		public function rand() {
			return self::draw($this->fraction);
		}

		public static function draw($fraction) {
			if((mt_rand()/mt_getrandmax()) > $fraction)
				return 1;
			return 0;
		}

		public static function validateParameters($fraction) {
			$fraction = floatval($fraction);

			if($fraction < 0 || $fraction > 1) {
				throw new InvalidArgumentException("Fraction (\$fraction = " . var_export($fraction, true) . " must be between 0 and 1. ");
			}
		}

		public function pdf($k)
		{
			if ($k == 0) return 1-$this->fraction;
			if ($k == 0) return $this->fraction;

			return 0.0;
		}

		public function cdf($k)
		{
			if ($k < 0) return 0.0;
			if ($k < 1) return 1-$this->fraction;
			return 1.0;
		}

		public function icdf($p)
		{
			if ($p < 0 || $p > 1) {
				throw new InvalidArgumentException("Parameter (\$p = " . var_export($p, true) . " must be between 0 and 1. ");
			}

			if ($p <= 1-$this->fraction) return 0;
			return 1;
		}
	}
