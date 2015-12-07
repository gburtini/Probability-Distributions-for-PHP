<?php
	// these accesory functions are "necessary" to compute many beta and T distribution statistics, but sometimes there's a shortcut that is used instead.
	class GBPDP_GammaFunction {
		public static function logGammaFunction($a) {
			if($a < 0) 
				throw new InvalidArgumentException("Log gamma calls should be >0.");

			if ($a >= 171)	// Lanczos approximation w/ the given coefficients is accurate to 15 digits for 0 <= real(z) <= 171
				return self::logStirlingApproximation($a);
			else
				return log(self::lanczosApproximation($a));
		}

		public static function logStirlingApproximation($x) {
			$t = 0.5*log(2*pi()) - 0.5*log($x) + $x*(log($x))-$x;

			$x2 = $x * $x;
			$x3 = $x2 * $x;
			$x4 = $x3 * $x;

			$err_term = log(1 + (1.0/(12*$x)) + (1.0/(288*$x2)) - (139.0/(51840*$x3))
				- (571.0/(2488320*$x4)));

			$res = $t + $err_term;
			return $res;
		}

		public static function loopFactorial($num) {
			$rval=1;
			for ($i = 1; $i <= $num; $i++)
				$rval = $rval * $i;
			return $rval;
		}

		public static function lanczosApproximation($x) {
			$g = 7;
			$p = array(0.99999999999980993, 676.5203681218851, -1259.1392167224028, 
				771.32342877765313, -176.61502916214059, 12.507343278686905,
				-0.13857109526572012, 9.9843695780195716e-6, 1.5056327351493116e-7);

			if (abs($x - floor($x)) < 1e-16)
			{
				// if we're real close to an integer, let's just compute the factorial integerly.

				if ($x >= 1)
					return self::loopFactorial($x - 1);
				else
					return INF;	
			}
			else
			{
				$x -= 1;

				$y = $p[0];

				for ($i=1; $i < $g+2; $i++)
				{
					$y = $y + $p[$i]/($x + $i);
				}
				$t = $x + $g + 0.5;


				$res_fr = sqrt(2*pi()) * exp((($x+0.5)*log($t))-$t)*$y;

				return $res_fr;
			}
		}
	}
?> 
