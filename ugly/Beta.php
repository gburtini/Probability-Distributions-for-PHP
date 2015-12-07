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
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>. 
	 * 
	 * Other Credits
	 * -------------
	 * Interface and structure all (C) Giuseppe Burtini.
	 * Some work derived (with permission/license) from jStat - Javascript Statistical Library (MIT licensed).
	 * Some work derived (with permission/license) from Python Core (PSL licensed).
	 * Some work, especially advice, provided by Graeme Douglas.
	 */

	// NAMESPACE FREE VERSION: designed for PHP <5.3. Pretty much all classes get prefixed with GBPDP to produce pseudonamespacing.
	// the fancy namespaced versions are in the appropriate composered path (src/.../Distributions/Beta.php) and rely on this file.

	require_once dirname(__FILE__) . "/Gamma.php";
	require_once dirname(__FILE__) . "/Distribution.php";
	require_once dirname(__FILE__) . "/Accessories/GammaFunction.php";
	require_once dirname(__FILE__) . "/Accessories/BetaFunction.php";

	class GBPDP_Beta extends GBPDP_Distribution {
		public $alpha;
		public $beta;

		// create a Beta(α, β) distribution
		public function __construct($a, $b) {
			self::validateParameters($a, $b);
			
			$this->alpha = $a;
			$this->beta = $b;
		}
		
		public function icdf($p) {
			$x = 0;
			$a = 0;
			$b = 1;
			$precision = 1e-15;		// these two variables can be changed
			$maxiters = 100;		// and should probably be offered in a more configuration friendly way
			$iter_num = 0;

			// limiting the iter_num to 100 is a bit of a hack. I am not really sold.
			while ((($b - $a) > $precision) && ($iter_num < $maxiters))
			{
				$x = ($a + $b) / 2;

				if (GBPDP_BetaFunction::incompleteBetaFunction($x,$this->alpha,$this->beta) > $p)
					$b = $x;
				else
					$a = $x;
				$iter_num = $iter_num + 1;
			}

			return $x;
		}
		
		public function rand() { 
			return self::draw($this->alpha, $this->beta); 
		}

		public static function draw($a, $b) {
			$ag = GBPDP_Gamma::draw($a, 1);
			$bg = GBPDP_Gamma::draw($b, 1);
			
			return ($ag / ($ag+$bg));
		}

		public static function validateParameters($a, $b) {
			$a = floatval($a);
			$b = floatval($b);
			
			if($a <= 0 || $b <= 0) {
				throw new InvalidArgumentException("α (\$a = " . var_export($a, true) . "), β (\$b = " . var_export($b, true) . ") must each be greater than 0. ");
			}
		}
	}
	
