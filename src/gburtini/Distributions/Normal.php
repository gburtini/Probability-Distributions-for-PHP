<?php
	/*
	 * Normal Distribution - Statistical Distributions for PHP
	 *
	 * Copyright (C) 2015 Giuseppe Burtini <joe@iterative.ca>. 
	 *
	 * Other credits
	 * Box, Muller 1958 for the rand() method
	 * Michael Nickerson (2004), Thomas Ziegler for the icdf function.
	 */
	namespace gburtini\Distributions;
	require_once dirname(__FILE__) . "/../../../ugly/Normal.php";
	class Normal extends \GBPDP_Normal {}
