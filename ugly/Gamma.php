<?php
	/*
	 * Statistical Distributions for PHP - Gamma Distribution
	 *
	 * This gamma implementation requires that $alpha (aka shape) is greater than 0, rather than some old definitions that require it to be > -1.
	 *
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>.
	 *
	 * Other Credits
	 * -------------
	 * Interface and structure all (C) Giuseppe Burtini.
	 * Some work derived (with permission/license) from jStat - Javascript Statistical Library (MIT licensed).
	 * Some work derived (with permission/license) from Python Core (PSFL licensed).
	 * Some work, especially advice, provided by Graeme Douglas.
	 */

	require_once dirname(__FILE__) . "/Distribution.php";

	class GBPDP_Gamma extends GBPDP_Distribution {
		protected $shape;
		protected $rate;
		public function __construct($shape, $rate) {
			$this->shape = floatval($shape);
			$this->rate = floatval($rate);
		}
		public function rand() {
			return self::draw($this->shape, $this->rate);
		}
		public static function draw($shape, $rate) {
			// This is a translation of Python Software Foundation licensed code from the Python project.

			$alpha = $shape;
			$beta = $rate;
			self::validateParameters($alpha, $beta);

			if($alpha > 1) {
				// Uses R.C.H. Cheng, "The generation of Gamma variables with non-integral shape parameters", Applied Statistics, (1977), 26, No. 1, p71-74
				$ainv = sqrt(2.0 * $alpha - 1.0);
				$bbb = $alpha - log(4.0);
				$ccc = $alpha + $ainv;

				while (true) {
					$u1 = rand() / getrandmax();

					if (!((1e-7 < $u1) && ($u1 < 0.9999999))) {
						continue;
					}

					$u2 = 1.0 - (rand()/getrandmax());
					$v = log($u1 / (1.0-$u1))/$ainv;
					$x = $alpha * exp($v);
					$z = $u1 * $u1 * $u2;
					$r = $bbb+$ccc*$v-$x;
					$SG_MAGICCONST = 1 + log(4.5);
					if ($r + $SG_MAGICCONST - 4.5*$z >= 0.0 || $r >= log($z)) {
						return $x * $beta;
					}
				}
			} else if ($alpha == 1.0) {
				$u = rand()/getrandmax();
				while ($u <= 1e-7) {
					$u = rand()/getrandmax();
				}
				return -log($u) * $beta;
			} else { // 0 < alpha < 1
				// Uses ALGORITHM GS of Statistical Computing - Kennedy & Gentle
				while (true) {
					$u3 = rand()/getrandmax();
					$b = (M_E + $alpha)/M_E;
					$p = $b*$u3;
					if ($p <= 1.0) {
						$x = pow($p, (1.0/$alpha));
					}
					else {
						$x = log(($b-$p)/$alpha);
					}
					$u4 = rand()/getrandmax();
					if ($p > 1.0) {
						if ($u4 <= pow($x, ($alpha - 1.0))) {
							break;
						}
					}
					else if ($u4 <= exp(-$x)) {
						break;
					}
				}
				return $x * $beta;
			}
		}

		public static function validateParameters($a, $b) {
			$a = floatval($a);
			$b = floatval($b);

			if($a <= 0 || $b <= 0) {
				throw new InvalidArgumentException("Alpha and beta must be greater than 0.");
			}
		}
	}

?>
