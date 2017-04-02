<?php
	require_once dirname(__FILE__) . "/../src/gburtini/Distributions/Beta.php";

	use gburtini\Distributions\Beta;

	class BetaDistributionTest extends PHPUnit_Framework_TestCase
	{
		public function testBetaInstantiateDistribution() {
			$jeffreys = new Beta(0.5, 0.5);	// corresponds to a binomial.
			$bayes = new Beta(1, 1);
		}


		public function testBetaInvalidInstantiation() {
			$this->setExpectedException('InvalidArgumentException');
			$haldanes = new Beta(0, 0);	// 0, 0 is not a valid beta distribution (it has two inf singularities)
		}


		public function testBetaInvalidInstantiationNegative() {
			$this->setExpectedException('InvalidArgumentException');
			new Beta(-1, -1);
		}


		public function testBetaInvalidInstantiationNegativeOne() {
			$this->setExpectedException('InvalidArgumentException');
			new Beta(-1, 1);
		}

		public function testObjectDraw() {
			srand(1);	// fix the random key, just for consistency.

			$d = new Beta(0.5, 0.5);
		
			$scale = 5000;
			$draws = new SplFixedArray($scale);
			for($i = 0; $i < $scale; $i++) {
				$draws[$i] = $d->rand();
			}

			$number = array_sum((array) $draws) / count($draws);
			$this->assertEquals(0.5, $number, "Attempting to draw from B(0.5, 0.5) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);
		}

		public function testStrongEdgeCase() {
			srand(1);	// fix the random key, just for consistency.

			$d = new Beta(1, 10000);
		
			$scale = 1000;
			$draws = new SplFixedArray($scale);
			for($i = 0; $i < $scale; $i++) {
				$draws[$i] = $d->rand();
			}

			$number = array_sum((array) $draws) / count($draws);
			$this->assertEquals(1/10001, $number, "Attempting to draw from B(1, 10000) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);

		}

		public function testClassDraw() {
			srand(1);
			$scale = 5000;
                        $draws = new SplFixedArray($scale);
                        for($i = 0; $i < $scale; $i++) {
				$draws[$i] = Beta::draw(0.5, 0.5);
			}
			$number = array_sum((array) $draws) / count($draws);
                        $this->assertEquals(0.5, $number, "Attempting to draw statically from B(0.5, 0.5) {$scale} times gives us a value too far from the expected mean. This could be just random chance iff testObjectDraw failed too: if it didn't, this is a definite problem.", 0.01);
		}

		public function testICDF() {
			$d = new Beta(10, 5);
			$this->assertEquals(0.3726, $d->icdf(0.01), "Inverse CDF incorrect", 0.01);
			$this->assertEquals(0.6742, $d->icdf(0.5), "Inverse CDF incorrect",  0.01);
			$this->assertEquals(0.8981, $d->icdf(0.99), "Inverse CDF incorrect", 0.01);

		}
	}

