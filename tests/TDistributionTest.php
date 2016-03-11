<?php
require_once dirname(__FILE__) . "/../src/gburtini/Distributions/T.php";

use gburtini\Distributions\T;

class TDistributionTest extends PHPUnit_Framework_TestCase
{
	public function test_T_InstantiateDistribution() {
		$distribution1 = new T(5.0);
		$distribution1 = new T(5.2);
	}

	public function test_T_InvalidInstantiation() {
		$this->setExpectedException('InvalidArgumentException');
		$invalid = new T(0);
	}

	public function test_T_InvalidInstantiation2() {
		$this->setExpectedException('InvalidArgumentException');
		$invalid = new T(-1);
	}

	public function test_T_InvalidInstantiation3() {
		$this->setExpectedException('InvalidArgumentException');
		$invalid = new T("a");
	}

	/* Not implemented */

	/*
	public function testObjectDraw() {
		mt_srand(1);

		$d = new T(6);

		$scale = 50000;
		$cutoff = 0.4;
		$counter = 0;

		$draws = new SplFixedArray($scale);
		for($i = 0; $i < $scale; $i++) {
			$x = $d->rand();
			$draws[$i] = $x;

			if ($x > $cutoff) $counter = $counter + 1;

		}

		$number = array_sum((array) $draws) / count($draws);
		$this->assertEquals($number,0, "Attempting to draw from T(6) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);

		$p = $counter / $scale;
		$this->assertEquals($p,1-$d->cdf($cutoff), "Attempting to draw from T(6) {$scale} times gives the wrong number of values greater than {$cutoff}. This could be just random chance.", 0.01);
	}
	*/

	/* Not implemented */
	/*

	public function testClassDraw() {
	mt_srand(1);

	$scale = 50000;
	$draws = new SplFixedArray($scale);
	for($i = 0; $i < $scale; $i++) {
	$draws[$i] = T::draw(6);
}

$number = array_sum((array) $draws) / count($draws);
$this->assertEquals($number,0, "Attempting to draw from T(6) {$scale} times gives us a value too far from the expected mean. This could be just random chance.", 0.01);
}
*/

public function test_T_PDF() {
	$d = new T(6);

	$accuracy = 1e-8;
	// This one only passes at an accuracy of 1e-5
	$this->assertEquals(0.001220840981, $d->pdf(-5), "PDF incorrect", $accuracy);

	$this->assertEquals(0.03147376634, $d->pdf(-2.5), "PDF incorrect", $accuracy);
	$this->assertEquals(0.3317760005, $d->pdf(-0.5), "PDF incorrect", $accuracy);
	$this->assertEquals(0.1255556346, $d->pdf(1.5), "PDF incorrect", $accuracy);
	$this->assertEquals(0.007798390662, $d->pdf(3.5), "PDF incorrect", $accuracy);
}

public function test_T_PDF_SpecialCases()
{
	$accuracy = 1e-10;

	$d = new T(1);
	$this->assertEquals(0.00315158303152267991, $d->pdf(-10), "PDF incorrect", $accuracy);
	$this->assertEquals(0.0122426879301457950, $d->pdf(-5), "PDF incorrect", $accuracy);
	$this->assertEquals(0.0636619772367581342, $d->pdf(-2), "PDF incorrect", $accuracy);
	$this->assertEquals(0.0187241109519876865, $d->pdf(4), "PDF incorrect", $accuracy);
	$this->assertEquals(0.00489707517205831801, $d->pdf(8), "PDF incorrect", $accuracy);
	$this->assertEquals(0.000979415034411663602, $d->pdf(18), "PDF incorrect", $accuracy);

	$d = new T(2);
	$this->assertEquals(0.000970732885271249327, $d->pdf(-10), "PDF incorrect", $accuracy);
	$this->assertEquals(0.00712778110110649090, $d->pdf(-5), "PDF incorrect", $accuracy);
	$this->assertEquals(0.0680413817439771695, $d->pdf(-2), "PDF incorrect", $accuracy);
	$this->assertEquals(0.0130945700219731023, $d->pdf(4), "PDF incorrect", $accuracy);
	$this->assertEquals(0.00186502259059595050, $d->pdf(8), "PDF incorrect", $accuracy);
	$this->assertEquals(0.000169892262460647639, $d->pdf(18), "PDF incorrect", $accuracy);

	$d = new T(3);
	$this->assertEquals(0.000311808216847087594, $d->pdf(-10), "PDF incorrect", $accuracy);
	$this->assertEquals(0.00421935379149330649, $d->pdf(-5), "PDF incorrect", $accuracy);
	$this->assertEquals(0.0675096606638929040, $d->pdf(-2), "PDF incorrect", $accuracy);
	$this->assertEquals(0.00916336114274446620, $d->pdf(4), "PDF incorrect", $accuracy);
	$this->assertEquals(0.000736906520946926330, $d->pdf(8), "PDF incorrect", $accuracy);
	$this->assertEquals(0.0000309361667324182615, $d->pdf(18), "PDF incorrect", $accuracy);

}

public function test_T_CDF() {

	$accuracy = 1e-8;

	/* Test data generated with SciPy */
	$testCases = array(
		array( 'n' =>  8 , 'p' =>  -0.680102332786 , 'T' =>  0.257817219023 ),
		array( 'n' =>  5 , 'p' =>  0.017259389709 , 'T' =>  0.506551389486 ),
		array( 'n' =>  8 , 'p' =>  -2.56894365278 , 'T' =>  0.0165910219718 ),
		array( 'n' =>  1 , 'p' =>  5.29241927437 , 'T' =>  0.94055631068 ),
		array( 'n' =>  8 , 'p' =>  0.311311369488 , 'T' =>  0.618239166029 ),
		array( 'n' =>  7 , 'p' =>  -3.19175773661 , 'T' =>  0.00761968204813 ),
		array( 'n' =>  6 , 'p' =>  -3.7294543521 , 'T' =>  0.00487147163693 ),
		array( 'n' =>  5 , 'p' =>  2.90807742674 , 'T' =>  0.983262757093 ),
		array( 'n' =>  1 , 'p' =>  -3.67167083222 , 'T' =>  0.0846405233833 ),
		array( 'n' =>  1 , 'p' =>  2.57869085848 , 'T' =>  0.882244569042 ),
		array( 'n' =>  7 , 'p' =>  0.434988137756 , 'T' =>  0.66166667186 ),
		array( 'n' =>  7 , 'p' =>  -0.588451443052 , 'T' =>  0.28735837801 ),
		array( 'n' =>  4 , 'p' =>  4.2443373534 , 'T' =>  0.993391161411 ),
		array( 'n' =>  7 , 'p' =>  -3.9725117135 , 'T' =>  0.00268751558773 ),
		array( 'n' =>  6 , 'p' =>  -3.56912796805 , 'T' =>  0.00589828732555 ),
		array( 'n' =>  5 , 'p' =>  0.115825605126 , 'T' =>  0.543850581555 ),
		array( 'n' =>  9 , 'p' =>  3.1806273475 , 'T' =>  0.99441324343 ),
		array( 'n' =>  1 , 'p' =>  5.61306261811 , 'T' =>  0.943880025072 ),
		array( 'n' =>  5 , 'p' =>  -1.23104080029 , 'T' =>  0.136525895371 ),
		array( 'n' =>  3 , 'p' =>  -3.76604082516 , 'T' =>  0.0163771654084 ),
		array( 'n' =>  5 , 'p' =>  -1.26651783788 , 'T' =>  0.130560932146 ),
		array( 'n' =>  5 , 'p' =>  -0.161972878326 , 'T' =>  0.438834612674 ),
		array( 'n' =>  6 , 'p' =>  2.17404612146 , 'T' =>  0.963672313695 ),
		array( 'n' =>  7 , 'p' =>  -2.29075246827 , 'T' =>  0.0278728337336 ),
		array( 'n' =>  4 , 'p' =>  -3.80876375143 , 'T' =>  0.0094801326226 ),
	);

	foreach($testCases as $test) {
		$n = $test['n'];
		$p = $test['p'];
		$cdf = $test['T'];
		$T = new T($n);
		$this->assertEquals($cdf, $T->cdf($p), "Expected T distribution CDF, T($n)::cdf($p) not matching", $accuracy);
	}
}

public function test_T_CDF_SpecialCases() {

	$accuracy = 1e-10;

	$d = new T(1);
	$this->assertEquals(0.031725517430553570, $d->cdf(-10), "CDF incorrect", $accuracy);
	$this->assertEquals(0.062832958189001184, $d->cdf(-5), "CDF incorrect", $accuracy);
	$this->assertEquals(0.147583617650433274, $d->cdf(-2), "CDF incorrect", $accuracy);
	$this->assertEquals(0.922020869622630672, $d->cdf(4), "CDF incorrect", $accuracy);
	$this->assertEquals(0.960416575839434458, $d->cdf(8), "CDF incorrect", $accuracy);
	$this->assertEquals(0.982334277111865365, $d->cdf(18), "CDF incorrect", $accuracy);

	$d = new T(2);
	$this->assertEquals(0.004926228511662848, $d->cdf(-10), "CDF incorrect", $accuracy);
	$this->assertEquals(0.018874775675311861, $d->cdf(-5), "CDF incorrect", $accuracy);
	$this->assertEquals(0.091751709536136983, $d->cdf(-2), "CDF incorrect", $accuracy);
	$this->assertEquals(0.971404520791031683, $d->cdf(4), "CDF incorrect", $accuracy);
	$this->assertEquals(0.992365963917330930, $d->cdf(8), "CDF incorrect", $accuracy);
	$this->assertEquals(0.998463898059540174, $d->cdf(18), "CDF incorrect", $accuracy);

}

public function test_T_ICDF() {

	$accuracy = 1e-8;

	/* Test data generated with SciPy */
	$testCases = array(
		array( 'n' =>  6 , 'p' =>  0.879284787062 , 'invT' =>  1.29961008579 ),
		array( 'n' =>  2 , 'p' =>  0.843913242264 , 'invT' =>  1.34008382669 ),
		array( 'n' =>  9 , 'p' =>  0.233067230215 , 'invT' =>  -0.760987228041 ),
		array( 'n' =>  4 , 'p' =>  0.9670349944 , 'invT' =>  2.51180875478 ),
		array( 'n' =>  2 , 'p' =>  0.716294746593 , 'invT' =>  0.678549285142 ),
		array( 'n' =>  7 , 'p' =>  0.871247830888 , 'invT' =>  1.23262561056 ),
		array( 'n' =>  5 , 'p' =>  0.0152534666589 , 'invT' =>  -2.98830059533 ),
		array( 'n' =>  4 , 'p' =>  0.740696920029 , 'invT' =>  0.706949924599 ),
		array( 'n' =>  9 , 'p' =>  0.204100018155 , 'invT' =>  -0.867504497711 ),
		array( 'n' =>  3 , 'p' =>  0.525291739665 , 'invT' =>  0.0688837423678 ),
		array( 'n' =>  8 , 'p' =>  0.659134186637 , 'invT' =>  0.425436764468 ),
		array( 'n' =>  8 , 'p' =>  0.270827452522 , 'invT' =>  -0.637427981495 ),
		array( 'n' =>  8 , 'p' =>  0.559736978418 , 'invT' =>  0.155176409395 ),
		array( 'n' =>  1 , 'p' =>  0.190698821781 , 'invT' =>  -1.46452880035 ),
		array( 'n' =>  8 , 'p' =>  0.22176437763 , 'invT' =>  -0.806019697834 ),
		array( 'n' =>  7 , 'p' =>  0.533941308053 , 'invT' =>  0.0882920817076 ),
		array( 'n' =>  6 , 'p' =>  0.0243368243103 , 'invT' =>  -2.4667211904 ),
		array( 'n' =>  1 , 'p' =>  0.0928805734187 , 'invT' =>  -3.32926694967 ),
		array( 'n' =>  5 , 'p' =>  0.268059720894 , 'invT' =>  -0.663931182273 ),
		array( 'n' =>  3 , 'p' =>  0.342665568458 , 'invT' =>  -0.446759389566 ),
		array( 'n' =>  4 , 'p' =>  0.747463488097 , 'invT' =>  0.731404873683 ),
		array( 'n' =>  1 , 'p' =>  0.988979844159 , 'invT' =>  28.8727949876 ),
		array( 'n' =>  5 , 'p' =>  0.923849131605 , 'invT' =>  1.68755019344 ),
		array( 'n' =>  3 , 'p' =>  0.47119286863 , 'invT' =>  -0.078482762954 ),
		array( 'n' =>  1 , 'p' =>  0.454278082698 , 'invT' =>  -0.144635735278 ),
	);

	foreach($testCases as $test) {
		$n = $test['n'];
		$p = $test['p'];
		$invT = $test['invT'];
		$T = new T($n);
		$this->assertEquals($invT, $T->icdf($p), "Expected T distribution inverse CDF, T($n)::icdf($p) not matching", $accuracy);
	}



}

public function test_T_CDF_ICDF() {

	$accuracy = 1e-8;

	for($i=0;$i<100;$i++) {
		$n = mt_rand(1, 10);
		$d = new T($n);
		$p = mt_rand()/mt_getrandmax();
		$this->assertEquals($p,$d->cdf($d->icdf($p)), "CDF and inverse CDF mismatch", $accuracy);
	}
}

}
