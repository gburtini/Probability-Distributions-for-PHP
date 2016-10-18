<?php
	/*
	 * Inverse Normal Distribution - Statistical Distributions for PHP
	 * see: https://en.wikipedia.org/wiki/Inverse_Gaussian_distribution#Generating_random_variates_from_an_inverse-Gaussian_distribution
	 */

	require_once dirname(__FILE__) . "/Normal.php";
	require_once dirname(__FILE__) . "/Distribution.php";


	class GBPDP_InverseNormal extends GBPDP_Distribution {
		public static function draw($mu, $lambda) {
			$norm = new GBPDP_Normal(0, 1);
			$v = $norm->draw(0, 1);
			$y = pow($v, 2);
			$x = $mu + (pow($mu, 2) * $y) / (2 * $lambda) - ($mu / (2 * $lambda)) * sqrt((4 * $mu * $lambda * $y) + (pow($mu, 2) * pow($y, 2)));
			$z = mt_rand()/mt_getrandmax();
			if ($z <= ($mu / ($mu + $x) )) {
				return $x;
			} else {
				return (pow($mu, 2) / $x);
			}   
		}
	}
