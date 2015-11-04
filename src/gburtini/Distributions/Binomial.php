<?php
	/*
	 * Probability Distributions for PHP - Binomial Distribution
	 *
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>. 
	 */

	namespace gburtini\Distributions;
	use gburtini\Distributions\Distribution;
	class Binomial extends Distribution {
		public $fraction;

		public function __construct($fraction) {
			static::validateParameters($fraction);
			
			$this->fraction = $fraction;
		}
		
		public function rand() { 
			return static::draw($this->fraction); 
		}

		public static function draw($fraction) {
			if((mt_rand()/mt_getrandmax()) > $fraction) 
				return 1;
			return 0;
		}

		public function sampleConfidenceInterval($sampleSize, $gamma=0.95, $t=true) {
			// proportion CI w/ Jeffrey's prior on a beta aka "Jeffrey's interval"
			

                }

		public static function validateParameters($fraction) {
			$fraction = floatval($fraction);
			
			if($fraction < 0 || $fraction > 1) {
				throw new \InvalidArgumentException("Fraction (\$fraction = " . var_export($fraction, true) . " must be between 0 and 1. ");
			}
		}
	}
	
