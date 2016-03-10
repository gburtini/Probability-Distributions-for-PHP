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
	 * $poisson = new Poisson($lambda>0);
	 * $poisson->pdf($x) = [0,1]
	 * $poisson->cdf($x) = [0,1] non-decreasing
	 * $poisson::quantile($y in [0,1]) = [0,1] (aliased Poisson::icdf)
	 * $poisson->rand() = [0,1]
	 *
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>.
	 */

	namespace gburtini\Distributions;
	require_once dirname(__FILE__) . "/../../../ugly/Poisson.php";	// we have to get this from the ugly implementation for PHP 5.2 support.
	class Poisson extends \GBPDP_Poisson {}
