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

	namespace gburtini\Distributions;
	require_once dirname(__FILE__) . "/../../../ugly/Gamma.php";
	class Gamma extends GBPDP_Gamma {}
