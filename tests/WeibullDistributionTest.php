<?php
require_once dirname(__FILE__) . "/../src/gburtini/Distributions/Weibull.php";

use gburtini\Distributions\Weibull;

class WeibullDistributionTest extends PHPUnit_Framework_TestCase
{
	public function testWeibullInstantiateDistribution() {
		$distribution1 = new Weibull(2.0, 3.1);
	}

	public function testWeibullInvalidInstantiation() {
		$this->setExpectedException('InvalidArgumentException');
		$invalid = new Weibull(0, 1);
	}

	public function testWeibullInvalidInstantiation2() {
		$this->setExpectedException('InvalidArgumentException');
		$invalid = new Weibull(1, 0);
	}

	public function testWeibullInvalidInstantiation3() {
		$this->setExpectedException('InvalidArgumentException');
		$invalid = new Weibull(1, -1);
	}

	public function testWeibullInvalidInstantiation4() {
		$this->setExpectedException('InvalidArgumentException');
		$invalid = new Weibull(-1, 1);
	}

	public function testWeibullInvalidInstantiation5() {
		$this->setExpectedException('InvalidArgumentException');
		$invalid = new Weibull("a", 2);
	}

	public function testObjectDraw() {
		mt_srand(1);

		$d = new Weibull(3.2, 1.4);

		$scale = 50000;
		$cutoff = 0.9;
		$counter = 0;

		$draws = new SplFixedArray($scale);
		for($i = 0; $i < $scale; $i++) {
			$x = $d->rand();
			$draws[$i] = $x;

			if ($x > $cutoff) $counter = $counter + 1;
		}


		// These perform differently on PHP <= 7.0. I think the RNG changed: As of PHP 7.1.0, rand() uses the same random number generator as mt_rand(). To preserve backwards compatibility rand() allows max to be smaller than min as opposed to returning FALSE as mt_rand(). 
		$number = array_sum((array) $draws) / count($draws);
		$mean = $d->mean();
		$this->assertEquals($number, $mean, "Attempting to draw from Weibull(3.2, 1.4)) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.015);

		$p = $counter / $scale;
		$this->assertEquals($p, 1 - $d->cdf($cutoff), "Attempting to draw from  Weibull(3.2, 1.4))  {$scale} times gives the wrong number of values greater than {$cutoff}. This could be just random chance.", 0.015);
	}

	public function testWeibullPDF() {
		$accuracy = 1e-10;

		$d = new Weibull(3.2, 1.4);

		$this->assertEquals(0, $d->pdf(-0.5), "PDF incorrect", $accuracy);
		$this->assertEquals(0, $d->pdf(0), "PDF incorrect", $accuracy);
		$this->assertEquals(0.228650585918, $d->pdf(0.5), "PDF incorrect", $accuracy);
		$this->assertEquals(0.775478835655, $d->pdf(1.0), "PDF incorrect", $accuracy);
		$this->assertEquals(0.764462234801, $d->pdf(1.5), "PDF incorrect", $accuracy);
		$this->assertEquals(0.218786337871, $d->pdf(2.0), "PDF incorrect", $accuracy);
		$this->assertEquals(0.0136761908489, $d->pdf(2.5), "PDF incorrect", $accuracy);

		$d = new Weibull(0.2, 3.0);

		$this->assertEquals(0, $d->pdf(-0.5), "PDF incorrect", $accuracy);
		$this->assertEquals(INF, $d->pdf(0), "PDF incorrect", $accuracy);
		$this->assertEquals(0.138973815055, $d->pdf(0.5), "PDF incorrect", $accuracy);
		$this->assertEquals(0.0719415041934, $d->pdf(1.0), "PDF incorrect", $accuracy);
		$this->assertEquals(0.0486023682443, $d->pdf(1.5), "PDF incorrect", $accuracy);
		$this->assertEquals(0.0366703766322, $d->pdf(2.0), "PDF incorrect", $accuracy);
		$this->assertEquals(0.0294110313310, $d->pdf(2.5), "PDF incorrect", $accuracy);
	}


	public function testWeibullCDF() {
		$accuracy = 1e-10;

		$d = new Weibull(3.2, 1.4);

		$this->assertEquals(0, $d->cdf(-0.5), "CDF incorrect", $accuracy);
		$this->assertEquals(0, $d->cdf(0), "CDF incorrect", $accuracy);
		$this->assertEquals(0.0363972185799547, $d->cdf(0.5), "CDF incorrect", $accuracy);
		$this->assertEquals(0.288737870976856, $d->cdf(1.0), "CDF incorrect", $accuracy);
		$this->assertEquals(0.712647485622632, $d->cdf(1.5), "CDF incorrect", $accuracy);
		$this->assertEquals(0.956326907614828, $d->cdf(2.0), "CDF incorrect", $accuracy);
		$this->assertEquals(0.998329075336515, $d->cdf(2.5), "CDF incorrect", $accuracy);

		$d = new Weibull(0.2, 3.0);

		$this->assertEquals(0, $d->cdf(-0.5), "CDF incorrect", $accuracy);
		$this->assertEquals(0, $d->cdf(0), "CDF incorrect", $accuracy);
		$this->assertEquals(0.502831918930007, $d->cdf(0.5), "CDF incorrect", $accuracy);
		$this->assertEquals(0.551901211916264, $d->cdf(1.0), "CDF incorrect", $accuracy);
		$this->assertEquals(0.581279046613598, $d->cdf(1.5), "CDF incorrect", $accuracy);
		$this->assertEquals(0.602320117031642, $d->cdf(2.0), "CDF incorrect", $accuracy);
		$this->assertEquals(0.618709033608360, $d->cdf(2.5), "CDF incorrect", $accuracy);
	}


	public function testWeibullICDF() {

		$accuracy = 1e-10;

		$d = new Weibull(3.2, 1.4);

		$this->assertEquals(0, $d->icdf(0), "ICDF incorrect", $accuracy);
		$this->assertEquals(0.793479152861220, $d->icdf(0.15), "ICDF incorrect", $accuracy);
		$this->assertEquals(1.01440930367139, $d->icdf(0.30), "ICDF incorrect", $accuracy);
		$this->assertEquals(1.19209305463711, $d->icdf(0.45), "ICDF incorrect", $accuracy);
		$this->assertEquals(1.36227077577260, $d->icdf(0.60), "ICDF incorrect", $accuracy);
		$this->assertEquals(1.55045035891072, $d->icdf(0.75), "ICDF incorrect", $accuracy);
		$this->assertEquals(1.81685571112213, $d->icdf(0.90), "ICDF incorrect", $accuracy);

		$d = new Weibull(0.2, 3.0);

		$this->assertEquals(0, $d->icdf(0), "ICDF incorrect", $accuracy);
		$this->assertEquals(0.000340126930429446, $d->icdf(0.15), "ICDF incorrect", $accuracy);
		$this->assertEquals(0.0173174627837282, $d->icdf(0.30), "ICDF incorrect", $accuracy);
		$this->assertEquals(0.229105337478594, $d->icdf(0.45), "ICDF incorrect", $accuracy);
		$this->assertEquals(1.93770528409560, $d->icdf(0.60), "ICDF incorrect", $accuracy);
		$this->assertEquals(15.3602589846856, $d->icdf(0.75), "ICDF incorrect", $accuracy);
		$this->assertEquals(194.177854510409, $d->icdf(0.90), "ICDF incorrect", $accuracy);
	}


	public function testWeibullMean() {
		$accuracy = 1e-10;

		$d = new Weibull(3.2, 1.4);
		$this->assertEquals(1.253915138, $d->mean(), "Mean incorrect", $accuracy);

		$d = new Weibull(0.2, 3.0);
		$this->assertEquals(360, $d->mean(), "Mean incorrect", $accuracy);
	}

	public function testWeibullVariance() {
		$accuracy = 1e-10;

		$d = new Weibull(3.2, 1.4);
		$this->assertEquals(0.1849824157, $d->variance(), "Variance incorrect", $accuracy);

		$d = new Weibull(0.2, 3.0);
		$this->assertEquals(3.25296000000e7, $d->variance(), "Variance incorrect", 1e-6);
		$this->assertEquals(sqrt(3.25296000000e7), $d->sd(), "Variance incorrect", 1e-6);


	}

	public function testWeibullCDFICDF() {
		$accuracy = 1e-8;

		for($i = 0; $i < 100; $i++) {
			$k = 3 * mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax();
			$lambda = 4 * mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax();
			$d = new Weibull($k, $lambda);
			$p = mt_rand() / mt_getrandmax();
			$this->assertEquals($p, $d->cdf($d->icdf($p)), "CDF and inverse CDF mismatch", $accuracy);
		}
	}

}
