<?php
	require_once dirname(__FILE__) . "/../src/gburtini/Distributions/Binomial.php";

	use gburtini\Distributions\Binomial;

	class BinomialDistributionTest extends PHPUnit_Framework_TestCase
	{
		public function testBinomialInstantiateDistribution() {
			$distribution1 = new Binomial(10,0.5);
			$distribution1 = new Binomial(6, 0.4);
		}
	
	
		public function testBinomialInvalidInstantiation() {
			$this->setExpectedException('InvalidArgumentException');
			$invalid = new Binomial(7.3, 0.5);
		}
	
		public function testBinomialInvalidInstantiation2() {
			$this->setExpectedException('InvalidArgumentException');
			$invalid = new Binomial(4, 1.2);
		}
	
		public function testObjectDraw() {
			mt_srand(1);
	
			$n = 10;
			$p = 0.3;
	
			$d = new Binomial($n, $p);
	
			$scale = 50000;
			$draws = new SplFixedArray($scale);
			for($i = 0; $i < $scale; $i++) {
				$draws[$i] = $d->rand();
			}
	
			$number = array_sum((array) $draws) / count($draws);
			$this->assertEquals($n*$p, $number, "Attempting to draw from Binom($n,$p) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);
		}
	
		public function testClassDraw() {
			mt_srand(1);
			
			$n = 10;
			$p = 0.3;
			
			$scale = 50000;
			$draws = new SplFixedArray($scale);
			for($i = 0; $i < $scale; $i++) {
			$draws[$i] = Binomial::draw($n, $p);
			}
			
			$number = array_sum((array) $draws) / count($draws);
			$this->assertEquals($n*$p, $number, "Attempting to draw from Binom($n,$p) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);
		}
		
		public function testBinomialPDF() {
			$d = new Binomial(14, 0.25);
			
			$this->assertEquals($d->pdf(0), 0.01781794801, "PDF incorrect", 1e-9);
			$this->assertEquals($d->pdf(1), 0.08315042407, "PDF incorrect", 1e-9);
			$this->assertEquals($d->pdf(5), 0.1467964277, "PDF incorrect", 1e-9);
			$this->assertEquals($d->pdf(9), 0.001812301576, "PDF incorrect", 1e-9);
		}
		
		public function testBinomialCDF() {
			$d = new Binomial(14, 0.25);
			
			$this->assertEquals($d->cdf(0), 0.01781794801, "CDF incorrect", 1e-9);
			$this->assertEquals($d->cdf(1), 0.1009683721, "CDF incorrect", 1e-9);
			$this->assertEquals($d->cdf(2), 0.2811276242, "CDF incorrect", 1e-9);
			$this->assertEquals($d->cdf(4), 0.7415346019, "CDF incorrect", 1e-9);
			$this->assertEquals($d->cdf(6), 0.9617292434, "CDF incorrect", 1e-9);
			$this->assertEquals($d->cdf(10), 0.9999601766, "CDF incorrect", 1e-9);
		}
		
		public function testBinomialICDF() {
			$d = new Binomial(50, 0.4);
			
			$this->assertEquals($d->icdf(0), 0, "Inverse CDF incorrect");
			$this->assertEquals($d->icdf(0.1), 16, "Inverse CDF incorrect");
			$this->assertEquals($d->icdf(0.3), 18, "Inverse CDF incorrect");
			$this->assertEquals($d->icdf(0.5), 20, "Inverse CDF incorrect");
			$this->assertEquals($d->icdf(0.90), 24, "Inverse CDF incorrect");
			$this->assertEquals($d->icdf(0.95), 26, "Inverse CDF incorrect");
			$this->assertEquals($d->icdf(0.99), 28, "Inverse CDF incorrect");
			$this->assertEquals($d->icdf(0.999), 31, "Inverse CDF incorrect");
			$this->assertEquals($d->icdf(0.99999),35, "Inverse CDF incorrect");
			$this->assertEquals($d->icdf(0.9999999), 38, "Inverse CDF incorrect");
		}

	}
