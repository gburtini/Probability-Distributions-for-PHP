<?php 
	interface IDistribution {
		public function pdf($x);
		public function pmf($x);
		public function cdf($x);
		public function icdf($y);
		public function quantile($y);
		public function rand();
	}
?>