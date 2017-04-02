<?php
	/*
	 * Probability Distributions for PHP - Beta Distribution
	 *
	 * This is an implementation of the beta distribution of the first kind, that is, the conjugate prior
	 * for the Bernoulli binomial and geometric distributions. It is defined solely on the interval [0,1]
	 * and parameterized on α>0, β>0.
	 *
	 * Use either as an instance variable or statically.
	 *
	 * use gburtini\Distributions\Beta;
	 *
	 * $beta = new Beta($alpha>0, $beta>0); 
	 * $beta->pdf($x) = [0,1]
	 * $beta->cdf($x) = [0,1] non-decreasing
	 * $beta::quantile($y in [0,1]) = [0,1] (aliased Beta::icdf)
	 * $beta->rand() = [0,1]
	 *
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>. 
	 * 
	 * Other Credits
	 * -------------
	 * Interface and structure all (C) Giuseppe Burtini.
	 * Some work derived (with permission/license) from jStat - Javascript Statistical Library (MIT licensed).
	 * Some work derived (with permission/license) from Python Core (PSL licensed).
	 * Some work, especially advice, provided by Graeme Douglas.
	 *
	 * Some Resources
	 * --------------
	 * http://cl.ly/code/1S0l1h0S2Y3B
	 * http://stackoverflow.com/questions/9590225/is-there-a-library-to-generate-random-numbers-according-to-a-beta-distribution-f
	 */

	namespace gburtini\Distributions;
	require_once dirname(__FILE__) . "/../../../ugly/Beta.php";	// we have to get this from the ugly implementation for PHP 5.2 support.

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

	class Beta extends \GBPDP_Beta {}	
