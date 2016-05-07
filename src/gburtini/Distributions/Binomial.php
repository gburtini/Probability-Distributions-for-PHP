<?php
	/*
	 * Probability Distributions for PHP - Binomial Distribution
	 *
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>.
	 */

	namespace gburtini\Distributions;
	require_once dirname(__FILE__) . "/../../../ugly/Binomial.php";
	class Binomial extends \GBPDP_Binomial {}
	class BinomialCI extends \GBPDP_Binomial_CI {};	// ugly.
