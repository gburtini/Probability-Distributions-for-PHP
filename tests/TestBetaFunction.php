<?php
	require_once dirname(__FILE__) . "/../src/gburtini/Distributions/Accessories/BetaFunction.php";

	use gburtini\Distributions\Accessories\BetaFunction;

	class BetaFunctionTest extends PHPUnit_Framework_TestCase
	{
		public function testBetaFunction() {
			$this->assertEquals(1/12, BetaFunction::betaFunction(2,3), "Expected beta function to perform appropriately B(2,3)=1/12.", 0.0001);
			$this->assertEquals(4.4776, BetaFunction::betaFunction(1.5, 0.2), "Expected beta function to perform appropriately B(1.5, 0.2)=4.477.", 0.0001);
		}


		// NOTE: this function is regularized (B_x / B)
		public function testInverseIncompleteBetaFunction() {
			$this->assertEquals(0.1120959, BetaFunction::inverseIncompleteBetaFunction(0.3, 1, 3), "Check behavior of regularized inverse incomplete beta function (I_x(1, 3) at 0.3 should be ~0.112 according to Casio calculator)", 0.0001);

		}


		// NOTE: regularized
		public function testIncompleteBetaFunction() {
			$this->assertEquals(0.784, BetaFunction::incompleteBetaFunction(0.4, 1, 3), "Check behavior of regularized incomplete beta.", 0.0001);
		}


		public function testContinuedFraction() {

		}
	}

