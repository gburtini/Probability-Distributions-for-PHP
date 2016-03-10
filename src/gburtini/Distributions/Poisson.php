<?php
	/*
	 * Probability Distributions for PHP - Poisson Distribution
	 *
	 * This is an implementation of the Poisson distribution.
	 *
	 * Use either as an instance variable or statically.
	 *
	 * use gburtini\Distributions\Poisson;
	 *
	 * $poisson = new Poissin($lambda>0);
	 * $poisson->pdf($x) = [0,1]
	 * $poisson->cdf($x) = [0,1] non-decreasing
	 * $poisson::quantile($y in [0,1]) = [0,1] (aliased Poisson::icdf)
	 * $poisson->rand() = [0,1]
	 *
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>.
	 *
	 * Other Credits
	 * -------------
	 * Interface and structure all (C) Giuseppe Burtini.
	 * Some work derived (with permission/license) from jStat - Javascript Statistical Library (MIT licensed).
	 * Some work derived (with permission/license) from Python Core (PSL licensed).
	 * Some work, especially advice, provided by Graeme Douglas.
	 */

	namespace gburtini\Distributions;
	require_once dirname(__FILE__) . "/../../../ugly/Poisson.php";	// we have to get this from the ugly implementation for PHP 5.2 support.

	/*
		require_once dirname(__FILE__) . "/Gamma.php";
		require_once dirname(__FILE__) . "/Distribution.php";
		require_once dirname(__FILE__) . "/Accessories/GammaFunction.php";
		require_once dirname(__FILE__) . "/Accessories/BetaFunction.php";


		use gburtini\Distributions\Gamma;
		use gburtini\Distributions\Distribution;
		use gburtini\Distributions\Accessories\GammaFunction;
		use gburtini\Distributions\Accessories\BetaFunction;
	*/

	class Poisson extends \GBPDP_Poisson {}
