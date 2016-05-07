<?php
	require_once dirname(__FILE__) . "/../src/gburtini/Distributions/Binomial.php";

	use gburtini\Distributions\BinomialCI;

	class BinomialCITest extends PHPUnit_Framework_TestCase
	{
		public function testBinomialInstantiateDistribution() {
			$distribution1 = new BinomialCI(213, 0);
			$distribution1 = new BinomialCI(6, 0.4);
		}
	
	
		public function testBinomialInvalidInstantiation() {
			$this->setExpectedException('InvalidArgumentException');
			$invalid = new BinomialCI(7.3, 0.5);
		}
	
		public function testBinomialInvalidInstantiation2() {
			$this->setExpectedException('InvalidArgumentException');
			$invalid = new BinomialCI(4, 1.2);
		}

		public function testConfidenceIntervals() {
			$d = new BinomialCI(213, 0);
			$ci = $d->jeffreys(0.7);
			$this->assertEquals($ci['lower'], 0, "Jeffreys CI incorrect when probability is zero (should lock to zero).");
			$this->assertEquals($ci['upper'], 0.002515455, "Jeffreys CI upper bound incorrect when probability is zero.");

		}	

	}
