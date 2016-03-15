<?php
	require_once dirname(__FILE__) . "/../src/gburtini/Distributions/Poisson.php";

	use gburtini\Distributions\Poisson;

	class PoissonDistributionTest extends PHPUnit_Framework_TestCase
	{
		public function testPoissonInstantiateDistribution() {
			$distribution1 = new Poisson(0.5);
			$distribution1 = new Poisson(2.0);
		}


		public function testPoissonInvalidInstantiation() {
			$this->setExpectedException('InvalidArgumentException');
			$invalid = new Poisson(-1.0);
		}


		public function testObjectDraw() {
			mt_srand(1);

			$d = new Poisson(7.0);

			$scale = 50000;
			$cutoff = 10.0;
			$counter = 0;
			$draws = new SplFixedArray($scale);
			for($i = 0; $i < $scale; $i++) {
				$x = $d->rand();
				$draws[$i] = $x;

				if ($x > $cutoff) $counter = $counter + 1;
			}

			$number = array_sum((array) $draws) / count($draws);
			$this->assertEquals( $number,7.0, "Attempting to draw from P(7.0) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);

			$p = $counter / $scale;
			$this->assertEquals(1-$d->cdf($cutoff), $p, "Attempting to draw from P(7.0) {$scale} times gives the wrong number of values greater than {$cutoff}. This could be just random chance.", 0.01);

		}

	        public function testPoissonPDF() {
	            $d = new Poisson(2.5);
	
	            $this->assertEquals( 0.08208499862, $d->pdf(0), 1e-9);
	            $this->assertEquals( 0.2052124966, $d->pdf(1), 1e-9);
	            $this->assertEquals( 0.2565156207, $d->pdf(2), 1e-9);
	            $this->assertEquals( 0.2137630172, $d->pdf(3), 1e-9);
	            $this->assertEquals( 0.00001021426063,$d->pdf(12), 1e-12);
	        }
	
	        public function testPoissonCDF() {
	            $d = new Poisson(2.5);
	
	            $this->assertEquals( 0.08208499862,$d->cdf(0), 1e-9);
	            $this->assertEquals( 0.2872974952,$d->cdf(1), 1e-9);
	            $this->assertEquals( 0.5438131159,$d->cdf(2), 1e-9);
	            $this->assertEquals( 0.7575761331,$d->cdf(3), 1e-9);
	            $this->assertEquals( 0.8911780189,$d->cdf(4), 1e-9);
	            $this->assertEquals( 0.9579789618,$d->cdf(5), 1e-9);
	            $this->assertEquals( 0.9999976158,$d->cdf(12), 1e-9);
	        }
	
	        public function testPoissonICDF() {
	            $d = new Poisson(2.5);
	
	            $this->assertEquals($d->icdf(0), 0);
	            $this->assertEquals($d->icdf(0.082084998), 0);
	            $this->assertEquals($d->icdf(0.09), 1);
	            $this->assertEquals($d->icdf(0.50), 2);
	            $this->assertEquals($d->icdf(0.90), 5);
	            $this->assertEquals($d->icdf(0.95), 5);
	            $this->assertEquals($d->icdf(0.99), 7);
	            $this->assertEquals($d->icdf(0.9999), 10);
	            $this->assertEquals($d->icdf(0.999999), 13);
	            $this->assertEquals($d->icdf(0.99999999), 16);
	        }


		public function testClassDraw() {
            		mt_srand(1);

			$scale = 50000;
			$draws = new SplFixedArray($scale);
			for($i = 0; $i < $scale; $i++) {
				$draws[$i] = Poisson::draw(7.0);
			}

			$number = array_sum((array) $draws) / count($draws);
			$this->assertEquals(7.0, $number, "Attempting to draw from P(7.0) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);
		}

		public function testLambda() {
			mt_srand(1);

			for ($i = 0; $i < 100; $i++) {
				$p = mt_rand()/mt_getrandmax();
				$k = mt_rand(1, 10);
				$lambda = Poisson::lambda($k, $p);
				$P = new Poisson($lambda);
				$this->assertEquals($P->cdf($k), $p, "Inverse parameter computation in Poisson distribution giving unecxpected result. (\$k = $k, \$p = $p)", 1e-9);
			}
		}
	}
