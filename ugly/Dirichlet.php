<?php
	/*
	 * Statistical Distributions for PHP - Dirchlet Distribution
	 *
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>. 
	 */
	require_once dirname(__FILE__) . "/Distribution.php";
        require_once dirname(__FILE__) . "/Gamma.php";

	class GBPDP_Dirichlet extends GBPDP_Distribution {
		protected $k; 
		protected $alpha;
		protected $gammaDistributions;
		public function __construct(array $concentration, $number=null) {			
			if (!is_array($concentration)) {
				if($number !== null) {
         	   			$concentration = array_fill_keys(range(0,$number-1),$concentration);
	         	   	} else {
        	 	   		throw new InvalidArgumentException("Concentration wasn't a valid array and number not specified. Creating a dirichlet is impossible.");
				}
			}

			$this->alpha = $concentration;
		}

		public function rand() { 
			return self::draw($this->k, $this->alpha); 
		}

		public static function draw($alpha, $k=null) {	
			$draws = array();
			foreach ($alpha as $g) {
				$draws[] = GBPDP_Gamma::draw($g, 1);
			}

			$sum = array_sum($draws);

			// PHP 5.2 cannot use anonymous functions, so call create_function instead.
			return array_map(
				create_function('$draws,$sum', 'return $draws/$sum;'),

				/*function ($draws) use ($sum) {
					return $draws/$sum;
				},*/

				$draws, 
				array_fill(0, count($draws), $sum)
			);
		}
	}

?>
