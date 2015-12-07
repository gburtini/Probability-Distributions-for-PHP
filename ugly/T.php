<?php
	/*
	 * Probability Distributions for PHP - T Distribution
	 *
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca> except where otherwise noted.
	 *
	 * Other Credits
	 * -------------
	 * Some work derived (with permission/license) from jStat - Javascript Statistical Library (MIT licensed).
	 */

	class GBPDP_T extends GBPDP_Distribution {
		protected $degrees;
		public function __construct($dof) {
			$this->degrees = floatval($dof); // float, not integer: http://stats.stackexchange.com/questions/116511/explanation-for-non-integer-degrees-of-freedom-in-t-test-with-unequal-variances
			// TODO: some overflow problems to be dealt with here (large DOF)
		}
	
		public function pdf($x) {
			return (1 / (sqrt($this->degrees) * GBPDP_BetaFunction::betaFunction(0.5, $this->degrees/2))) * pow(1 + (($x*$x)/$this->degrees), -(($this->degrees + 1) / 2));
  		}

		public function cdf($x) {
			$halfDegrees = $this->degrees/2;
			return GBPDP_BetaFunction::incompleteBetaFunction( ($x + sqrt($x*$x + $this->degrees)) / (2 * sqrt($x*$x + $this->degrees)), $halfDegrees, $halfDegrees);
		}

		public function icdf($y) {
			$x = GBPDP_BetaFunction::inverseIncompleteBetaFunction(2 * min($y, 1 - $y), 0.5 * $this->degrees, 0.5);
			$x = sqrt($this->degrees * (1 - $y) / $y);
			return ($y > 0.5) ? $x : -$x;
		}
	}

?>
