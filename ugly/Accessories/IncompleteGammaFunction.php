<?php

// NAMESPACE FREE VERSION: for PHP <5.3
require_once dirname(__FILE__) . "/GammaFunction.php";

// these accesory functions are "necessary" to compute many beta and T distribution statistics, but sometimes there's a shortcut that is used instead.
class GBPDP_IncompleteGammaFunction {



	/*
	*
	* Incomplete Gamma integral
	*
	*
	*
	* SYNOPSIS:
	*
	* double a, x, y, igam();
	*
	* y = igam( a, x );
	*
	* DESCRIPTION:
	*
	* The function is defined by
	*
	*                           x
	*                            -
	*                   1       | |  -t  a-1
	*  igam(a,x)  =   -----     |   e   t   dt.
	*                  -      | |
	*                 | (a)    -
	*                           0
	*
	*
	* In this implementation both arguments must be positive.
	* The integral is evaluated by either a power series or
	* continued fraction expansion, depending on the relative
	* values of a and x.
	*
	* ACCURACY:
	*
	*                      Relative error:
	* arithmetic   domain     # trials      peak         rms
	*    IEEE      0,30       200000       3.6e-14     2.9e-15
	*    IEEE      0,100      300000       9.9e-14     1.5e-14
	*/
	/*							igamc()
	*
	*	Complemented incomplete Gamma integral
	*
	*
	*
	* SYNOPSIS:
	*
	* double a, x, y, igamc();
	*
	* y = igamc( a, x );
	*
	* DESCRIPTION:
	*
	* The function is defined by
	*
	*
	*  igamc(a,x)   =   1 - igam(a,x)
	*
	*                            inf.
	*                              -
	*                     1       | |  -t  a-1
	*               =   -----     |   e   t   dt.
	*                    -      | |
	*                   | (a)    -
	*                             x
	*
	*
	* In this implementation both arguments must be positive.
	* The integral is evaluated by either a power series or
	* continued fraction expansion, depending on the relative
	* values of a and x.
	*
	* ACCURACY:
	*
	* Tested at random a, x.
	*                a         x                      Relative error:
	* arithmetic   domain   domain     # trials      peak         rms
	*    IEEE     0.5,100   0,100      200000       1.9e-14     1.7e-15
	*    IEEE     0.01,0.5  0,100      200000       1.4e-13     1.6e-15
	*/

	/*
	* Cephes Math Library Release 2.0:  April, 1987
	* Copyright 1985, 1987 by Stephen L. Moshier
	* Direct inquiries to 30 Frost Street, Cambridge, MA 02140
	*/

	/* Ported to PHP by Frank Wikström. */

	static $MACHEP = 1.11022302462515654042E-16;
	static $MAXLOG = 7.08396418532264106224E2;
	static $big = 4.503599627370496e15;
	static $biginv = 2.22044604925031308085e-16;

	public static function ComplementedIncompleteGamma($a, $x)
	{
		if (($x < 0) || ($a <= 0)) {
			throw new InvalidArgumentException("Incomplete gamma function only implemented for positive values.");
		}

		if (($x < 1.0) || ($x < $a))
		return (1.0 - static::IncompleteGamma($a, $x));

		$ax = $a * log($x) - $x - GBPDP_GammaFunction::logGammaFunction($a);

		// Check for underflow
		if ($ax < -static::$MAXLOG) {
			return (0.0);
		}
		$ax = exp($ax);

		/* continued fraction */
		$y = 1.0 - $a;
		$z = $x + $y + 1.0;
		$c = 0.0;
		$pkm2 = 1.0;
		$qkm2 = $x;
		$pkm1 = $x + 1.0;
		$qkm1 = $z * $x;
		$ans = $pkm1 / $qkm1;

		do {
			$c += 1.0;
			$y += 1.0;
			$z += 2.0;
			$yc = $y * $c;
			$pk = $pkm1 * $z - $pkm2 * $yc;
			$qk = $qkm1 * $z - $qkm2 * $yc;

			if ($qk != 0) {
				$r = $pk / $qk;
				$t = abs(($ans - $r) / $r);
				$ans = $r;
			}
			else {
				$t = 1.0;
			}

			$pkm2 = $pkm1;
			$pkm1 = $pk;
			$qkm2 = $qkm1;
			$qkm1 = $qk;

			if (abs($pk) > static::$big) {
				$pkm2 *= static::$biginv;
				$pkm1 *= static::$biginv;
				$qkm2 *= static::$biginv;
				$qkm1 *= static::$biginv;
			}
		} while ($t > static::$MACHEP);

		return ($ans * $ax);
	}



	/* left tail of incomplete Gamma function:
	*
	*          inf.      k
	*   a  -x   -       x
	*  x  e     >   ----------
	*           -     -
	*          k=0   | (a+k+1)
	*
	*/

	public static function IncompleteGamma($a, $x)
	{
		/* Check zero integration limit first */
		if ($x == 0) return (0.0);

		if (($x < 0) || ($a <= 0)) {
			throw new InvalidArgumentException("Incomplete gamma function only implemented for positive values.");
		}

		if (($x > 1.0) && ($x > $a)) return (1.0 - static::ComplementedIncompleteGamma($a, $x));

		/* Compute  x**a * exp(-x) / Gamma(a)  */
		$ax = $a * log($x) - $x - GBPDP_GammaFunction::logGammaFunction($a);

		if ($ax < -static::$MAXLOG) {
			return (0.0);
		}

		$ax = exp($ax);

		/* power series */
		$r = $a;
		$c = 1.0;
		$ans = 1.0;

		do {
			$r += 1.0;
			$c *= $x / $r;
			$ans += $c;
		} while ($c / $ans > static::$MACHEP);

		return ($ans * $ax / $a);
	}


	/*
	*
	*      Inverse of complemented imcomplete Gamma integral
	*
	*
	*
	* SYNOPSIS:
	*
	* double a, x, p, igami();
	*
	* x = igami( a, p );
	*
	* DESCRIPTION:
	*
	* Given p, the function finds x such that
	*
	*  igamc( a, x ) = p.
	*
	* Starting with the approximate value
	*
	*         3
	*  x = a t
	*
	*  where
	*
	*  t = 1 - d - ndtri(p) sqrt(d)
	*
	* and
	*
	*  d = 1/9a,
	*
	* the routine performs up to 10 Newton iterations to find the
	* root of igamc(a,x) - p = 0.
	*
	* ACCURACY:
	*
	* Tested at random a, p in the intervals indicated.
	*
	*                a        p                      Relative error:
	* arithmetic   domain   domain     # trials      peak         rms
	*    IEEE     0.5,100   0,0.5       100000       1.0e-14     1.7e-15
	*    IEEE     0.01,0.5  0,0.5       100000       9.0e-14     3.4e-15
	*    IEEE    0.5,10000  0,0.5        20000       2.3e-13     3.8e-14
	*/

	/*
	* Cephes Math Library Release 2.3:  March, 1995
	* Copyright 1984, 1987, 1995 by Stephen L. Moshier
	*/


	public static function InverseComplementedIncompleteGamma($a, $y0)
	{

		/* bound the solution */
		$x0 = INF;
		$yl = 0;
		$x1 = 0;
		$yh = 1.0;
		$dithresh = 5.0 * static::$MACHEP;

		if (($y0 < 0.0) || ($y0 > 1.0) || ($a <= 0)) {
			throw new InvalidArgumentException("Invese complemented incomplete gamma function only implemented for \$a > 0 and \$y0 between 0 and 1.");
		}

		if ($y0 == 0.0) {
			return INF;
		}

		if ($y0 == 1.0) {
			return 0.0;
		}

		/* approximation to inverse function */
		$d = 1.0 / (9.0 * $a);

		$y = (1.0 - $d - static::InverseNormal($y0) * sqrt($d));

		$x = $a * $y * $y * $y;

		$lgm = GBPDP_GammaFunction::logGammaFunction($a);

		for ($i = 0; $i < 10; $i++) {
			if ($x > $x0 || $x < $x1) {
				break;
			}
			$y = static::ComplementedIncompleteGamma($a, $x);
			if ($y < $yl || $y > $yh) {
				break;
			}

			if ($y < $y0) {
				$x0 = $x;
				$yl = $y;
			} else {
				$x1 = $x;
				$yh = $y;
			}
			/* compute the derivative of the function at this point */
			$d = ($a - 1.0) * log($x) - $x - $lgm;
			if ($d < -static::$MAXLOG) break;
			$d = -exp($d);

			/* compute the step to the next approximation of x */
			$d = ($y - $y0) / $d;

			if (abs($d / $x) < static::$MACHEP) return $x;

			$x = $x - $d;

		}

		/* Resort to interval halving if Newton iteration did not converge. */

		$d = 0.0625;
		if (is_infinite($x0)) {
			if ($x <= 0.0) $x = 1.0;
			while (is_infinite($x0)) {
				$x = (1.0 + $d) * $x;
				$y = static::ComplementedIncompleteGamma($a, $x);
				if ($y < $y0) {
					$x0 = $x;
					$yl = $y;
					break;
				}
				$d = $d + $d;
				if ($d > 10) return INF;
			}
		}

		$d = 0.5;
		$dir = 0;

		for ($i = 0; $i < 400; $i++) {
			$x = $x1 + $d * ($x0 - $x1);
			$y = static::ComplementedIncompleteGamma($a, $x);
			$lgm = ($x0 - $x1) / ($x1 + $x0);

			if (abs($lgm) < $dithresh) break;

			$lgm = ($y - $y0) / $y0;
			if (abs($lgm) < $dithresh) break;

			if ($x <= 0.0) break;

			if ($y >= $y0) {
				$x1 = $x;
				$yh = $y;

				if ($dir < 0) {
					$dir = 0;
					$d = 0.5;
				}
				else if ($dir > 1) {
					$d = 0.5 * $d + 0.5;
				} else {
					$d = ($y0 - $yl) / ($yh - $yl);
				}

				$dir += 1;

			} else {

				$x0 = $x;
				$yl = $y;
				if ($dir > 0) {
					$dir = 0;
					$d = 0.5;
				} else if ($dir < -1) {
					$d = 0.5 * $d;
				} else {
					$d = ($y0 - $yl) / ($yh - $yl);
				}
				$dir -= 1;
			}
		}

		if ($x == 0.0) return 0.0;

		return $x;
	}

	/*
	*
	*     Inverse of Normal distribution function
	*
	*
	*
	* SYNOPSIS:
	*
	* double x, y, ndtri();
	*
	* x = ndtri( y );
	*
	*
	*
	* DESCRIPTION:
	*
	* Returns the argument, x, for which the area under the
	* Gaussian probability density function (integrated from
	* minus infinity to x) is equal to y.
	*
	*
	* For small arguments 0 < y < exp(-2), the program computes
	* z = sqrt( -2.0 * log(y) );  then the approximation is
	* x = z - log(z)/z  - (1/z) P(1/z) / Q(1/z).
	* There are two rational functions P/Q, one for 0 < y < exp(-32)
	* and the other for y up to exp(-2).  For larger arguments,
	* w = y - 0.5, and  x/sqrt(2pi) = w + w**3 R(w**2)/S(w**2)).
	*
	*
	* ACCURACY:
	*
	*                      Relative error:
	* arithmetic   domain        # trials      peak         rms
	*    IEEE     0.125, 1        20000       7.2e-16     1.3e-16
	*    IEEE     3e-308, 0.135   50000       4.6e-16     9.8e-17
	*
	*
	* ERROR MESSAGES:
	*
	*   message         condition    value returned
	* ndtri domain       x <= 0        -NPY_INFINITY
	* ndtri domain       x >= 1         NPY_INFINITY
	*
	*/


	/*
	* Cephes Math Library Release 2.1:  January, 1989
	* Copyright 1984, 1987, 1989 by Stephen L. Moshier
	* Direct inquiries to 30 Frost Street, Cambridge, MA 02140
	*/

	#include "mconf.h"

	/* sqrt(2pi) */
	static $s2pi = 2.50662827463100050242E0;

	/* approximation for 0 <= |y - 0.5| <= 3/8 */
	static $P0 = array(
		-5.99633501014107895267E1,
		9.80010754185999661536E1,
		-5.66762857469070293439E1,
		1.39312609387279679503E1,
		-1.23916583867381258016E0,
	);

	static $Q0 = array(
		1.00000000000000000000E0,
		1.95448858338141759834E0,
		4.67627912898881538453E0,
		8.63602421390890590575E1,
		-2.25462687854119370527E2,
		2.00260212380060660359E2,
		-8.20372256168333339912E1,
		1.59056225126211695515E1,
		-1.18331621121330003142E0,
	);

	/* Approximation for interval z = sqrt(-2 log y ) between 2 and 8
	* i.e., y between exp(-2) = .135 and exp(-32) = 1.27e-14.
	*/
	static $P1 = array(
		4.05544892305962419923E0,
		3.15251094599893866154E1,
		5.71628192246421288162E1,
		4.40805073893200834700E1,
		1.46849561928858024014E1,
		2.18663306850790267539E0,
		-1.40256079171354495875E-1,
		-3.50424626827848203418E-2,
		-8.57456785154685413611E-4,
	);

	static $Q1 = array(
		1.00000000000000000000E0,
		1.57799883256466749731E1,
		4.53907635128879210584E1,
		4.13172038254672030440E1,
		1.50425385692907503408E1,
		2.50464946208309415979E0,
		-1.42182922854787788574E-1,
		-3.80806407691578277194E-2,
		-9.33259480895457427372E-4,
	);

	/* Approximation for interval z = sqrt(-2 log y ) between 8 and 64
	* i.e., y between exp(-32) = 1.27e-14 and exp(-2048) = 3.67e-890.
	*/

	static $P2= array(
		3.23774891776946035970E0,
		6.91522889068984211695E0,
		3.93881025292474443415E0,
		1.33303460815807542389E0,
		2.01485389549179081538E-1,
		1.23716634817820021358E-2,
		3.01581553508235416007E-4,
		2.65806974686737550832E-6,
		6.23974539184983293730E-9,
	);

	static $Q2 = array(
		1.00000000000000000000E0,
		6.02427039364742014255E0,
		3.67983563856160859403E0,
		1.37702099489081330271E0,
		2.16236993594496635890E-1,
		1.34204006088543189037E-2,
		3.28014464682127739104E-4,
		2.89247864745380683936E-6,
		6.79019408009981274425E-9,
	);

	public static function InverseNormal($y0)
	{

		if ($y0 <= 0.0) {
			return -INF;
		}
		if ($y0 >= 1.0) {
			return INF;
		}
		$code = 1;
		$y = $y0;
		if ($y > (1.0 - 0.13533528323661269189)) {	/* 0.135... = exp(-2) */
			$y = 1.0 - $y;
			$code = 0;
		}

		if ($y > 0.13533528323661269189) {
			$y = $y - 0.5;
			$y2 = $y * $y;
			$x = $y + $y * ($y2 * static::polevl($y2, static::$P0) / static::polevl($y2, static::$Q0));
			$x = $x * static::$s2pi;
			return $x;
		}

		$x = sqrt(-2.0 * log($y));
		$x0 = $x - log($x) / $x;

		$z = 1.0 / $x;

		if ($x < 8.0) $x1 = $z * static::polevl($z, static::$P1) / static::polevl($z, static::$Q1);
		else $x1 = $z * static::polevl($z, static::$P2) / static::polevl($z, static::$Q2);

		$x = $x0 - $x1;
		if ($code != 0) $x = -$x;

		return $x;
	}

	private static function polevl($x, array $coeffs)
	{
		$ans = 0.0;
		foreach ($coeffs as $a) {
			$ans = $ans * $x + $a;
		}

	    return $ans;
	}

}
