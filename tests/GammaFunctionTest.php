<?php
	require_once dirname(__FILE__) . "/../src/gburtini/Distributions/Accessories/GammaFunction.php";

	use gburtini\Distributions\Accessories\GammaFunction;

	class GammaFunctionTest extends PHPUnit_Framework_TestCase
	{
		// NOTE: this uses the log stirling/lanczos implementations depending on whether $a is above 171 or not (accuracy threshold)
		public function testLogGammaFunction() {
			// $a

			// important to test non-integer calls to gamma on both sides (these may fall back to loopFactorial otherwise).

			$precision = 0.001;
			$this->assertEquals(0, GammaFunction::logGammaFunction(2), "Expecting logGamma of 2 to be 0.", $precision);
			$this->assertEquals(39.339, GammaFunction::logGammaFunction(20), "Expecting logGamma of 20 to be around 39.33. Note any potential overflows here (log(121,645,100,408,832,000)).", $precision);
			$this->assertEquals(1.20097, GammaFunction::logGammaFunction(3.5), "Decimal approximations are different than expected w/ log gamma.", $precision);

			// 171 is the threshold for changing approxmation.
			$this->assertEquals(701.43726, GammaFunction::logGammaFunction(170), "Failing on logGamma around 170.", $precision);
			$this->assertEquals(706.57306, GammaFunction::logGammaFunction(171), "Failing on logGamma around 171.", $precision);
			$this->assertEquals(711.714725, GammaFunction::logGammaFunction(172), "Failing on logGamma around 172.", $precision);
			$this->assertEquals(721.4999, GammaFunction::logGammaFunction(173.9), "Decimal approximations are different than expected w/ large log gamma.", $precision);

			$this->assertEquals(857.9336, GammaFunction::logGammaFunction(200), "Expecting logGamma of 200 to be around 857.933. Note any potential overflows here.", $precision);
		}

		public function testLogStirlingApproximation() {
			// $x
			// NOTE: this is only actually used above 171, so it doesn't need to be accurate below that. It is though.

			$precision = 0.001;
			$this->assertEquals(0, GammaFunction::logStirlingApproximation(2), "Expecting Stirling approx. to work at 2 to be 0.", $precision);
			$this->assertEquals(39.339, GammaFunction::logStirlingApproximation(20), "Expecting Stirling of 20 to be around 39.33. Note any potential overflows here (log(121,645,100,408,832,000)).", $precision);
			$this->assertEquals(701.43726, GammaFunction::logStirlingApproximation(170), "Failing on Stirling around 170.", $precision);
			$this->assertEquals(706.57306, GammaFunction::logStirlingApproximation(171), "Failing on Stirling around 171.", $precision);
			$this->assertEquals(711.714725, GammaFunction::logStirlingApproximation(172), "Failing on Stirling around 172.", $precision);

			$this->assertEquals(857.9336, GammaFunction::logStirlingApproximation(200), "Expecting Stirling of 200 to be around 857.933. Note any potential overflows here.", $precision);
			$this->assertEquals(1.20097, GammaFunction::logStirlingApproximation(3.5), "Decimal approximations are different than expected w/ Stirling.", $precision);
		}

		public function testLanczosApproximation() {
			// $x
			// this requires computing a massive number and then taking the log of it. Eventually, it starts returning INF, so we shouldn't expect it to work at that point. It is also not very precise.
			$precision = 0.01;
			$this->assertEquals(39.339, log(GammaFunction::LanczosApproximation(20)), "Expecting Lanczos to work around 20", $precision);
			$this->assertEquals(0, log(GammaFunction::LanczosApproximation(2)), "Expecting Lanczos to work around 2", $precision);
			$this->assertEquals(1.20097, log(GammaFunction::LanczosApproximation(3.5)), "Decimal approximations are different than expected w/ Lanczos.", $precision);
		}

		public function testLoopFactorial() {
			// $num
		}
	

	}

