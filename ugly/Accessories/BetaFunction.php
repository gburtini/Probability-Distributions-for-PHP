<?php
	// NAMESPACE FREE VERSION: for PHP <5.3
	require_once dirname(__FILE__) . "/GammaFunction.php";
	
	// these accesory functions are "necessary" to compute many beta distribution statistics, but we don't actually use them (here, they are used in the T distribution calculations currently) because the Gamma shortcut calculator is faster.
	class GBPDP_BetaFunction {
		public static function betaFunction($x, $y) {
			return exp(GBPDP_GammaFunction::logGammaFunction($x) + GBPDP_GammaFunction::logGammaFunction($y) - GBPDP_GammaFunction::logGammaFunction($x+$y));
		}

		public static function inverseIncompleteBetaFunction($p, $a, $b) {
			// from jStat.ibetainv
			$EPS = 1e-8;
			$a1 = $a - 1;
			$b1 = $b - 1;
			$j = 0;
			//$lna; $lnb; $pp; $t; $u; $err; $x; $al; $h; $w; $afac;
			if ($p <= 0)
				return 0;
			if ($p >= 1)
				return 1;
			if ($a >= 1 && $b >= 1) {
				$pp = ($p < 0.5) ? $p : 1 - $p;
				$t = sqrt(-2 * log($pp));
				$x = (2.30753 + $t * 0.27061) / (1 + $t* (0.99229 + $t * 0.04481)) - $t;
				if ($p < 0.5)
					$x = -$x;
				$al = ($x * $x - 3) / 6;
				$h = 2 / (1 / (2 * $a - 1)  + 1 / (2 * $b - 1));
				$w = ($x * sqrt($al + $h) / $h) - (1 / (2 * $b - 1) - 1 / (2 * $a - 1)) * ($al + 5 / 6 - 2 / (3 * $h));
				$x = $a / ($a + $b * exp(2 * $w));
			} else {
				$lna = log($a / ($a + $b));
				$lnb = log($b / ($a + $b));
				$t = exp($a * $lna) / $a;
				$u = exp($b * $lnb) / $b;
				$w = $t + $u;
				if ($p < $t / $w)
					$x = pow($a * $w * $p, 1 / $a);
				else
					$x = 1 - pow($b * $w * (1 - $p), 1 / $b);
			}
			$afac = -GBPDP_GammaFunction::logGammaFunction($a) - GBPDP_GammaFunction::logGammaFunction($b) + GBPDP_GammaFunction::logGammaFunction($a + $b);	
			for(; $j < 10; $j++) {
				if ($x === 0 || $x === 1)
					return $x;
				$err = self::incompleteBetaFunction($x, $a, $b) - $p;
				$t = exp($a1 * log($x) + $b1 * log(1 - $x) + $afac);
				$u = $err / $t;
				$x -= ($t = $u / (1 - 0.5 * min(1, $u * ($a1 / $x - $b1 / (1 - $x)))));
				if ($x <= 0)
					$x = 0.5 * ($x + $t);
				if ($x >= 1)
					$x = 0.5 * ($x + $t + 1);
				if (abs($t) < $EPS * $x && $j > 0)
					break;
			}
			return $x;
		}

                public static function incompleteBetaFunction($x, $a, $b) {
			$g_ab = GBPDP_GammaFunction::logGammaFunction($a+$b);
			$g_a = GBPDP_GammaFunction::logGammaFunction($a); 
			$g_b = GBPDP_GammaFunction::logGammaFunction($b);

			$bt = exp($g_ab - $g_a - $g_b + $a*log($x)+$b*log(1.0-$x));

			if ($x == 0)
				$bt = 0;

			if ($x < (($a + 1.0)/($a + $b + 2.0)))
				return $bt* self::continuedFraction($a,$b,$x)/$a;
			else
				return 1 - ($bt*self::continuedFraction($b,$a,1.0-$x)/$b);
		}

		// see http://functions.wolfram.com/GammaBetaErf/Beta3/10/
		public static function continuedFraction($a, $b, $x) {
			$maxit = 100;
			$eps = 3e-16;
			$fpmin = 1e-30;
			/*	
				// mentioning these causes E_NOTICEs in HipHop.
				$aa;
				$c;
				$d;
				$del;
				$h;
				$qab; 
				$qam;
				$qap;
			*/

			$qab = $a + $b;
			$qap = $a + 1;
			$qam = $a - 1;

			$c = 1.0;
			$d = 1.0 - $qab*$x/$qap;

			if (abs($d)<$fpmin)
				$d = $fpmin;

			$d = 1.0/$d;

			$h = $d;

			//$m2;

			for ($m = 1; $m < $maxit; $m++)
			{
				$m2 = 2*$m;
				$aa = $m*($b-$m)*$x/(($qam + $m2)*($a + $m2));
				$d = 1.0 + $aa*$d;

				if (abs($d)<$fpmin)
					$d = $fpmin;

				$c = 1.0 + $aa/$c;

				if (abs($c)<$fpmin)
					$c = $fpmin;

				$d = 1.0/$d;
				$h = $h*$d*$c;
				$aa = -($a + $m)*($qab + $m)*$x/(($a+$m2)*($qap+$m2));
				$d = 1.0 + $aa*$d;

				if (abs($d)<$fpmin)
					$d = $fpmin;

				$c = 1.0 + $aa/$c;

				if (abs($c)<$fpmin)
					$c = $fpmin;

				$d = 1.0/$d;
				$del = $d*$c;
				$h = $h*$del;

				if (abs($del-1.0)< $eps)
				{
					break;
				}
			}
			return $h;
		}

	}

?>
