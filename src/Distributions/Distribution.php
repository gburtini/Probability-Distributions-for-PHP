<?php
	abstract class Distribution implements IDistribution {
		abstract public function pdf($x);
		public function pmf($x) { return $this->pdf($x); }
		abstract public function cdf($x);
		abstract public function icdf($y);
		abstract public function rand();
		public function rands($n) {
			// generate $n random numbers.
			$return = new SplFixedArray($n);
			for($i = 0; $i < $n; $i++) {
				$return[$i] = $this->rand();
			}
			return $return;
		}
		public function quantile($y) {
			return $this->icdf($y);
		}
	}
?>