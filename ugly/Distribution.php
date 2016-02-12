<?php
	abstract class GBPDP_Distribution {
		public function pdf($x) {
			throw new BadMethodCallException("PDF not implemented. Please create a pull request if you implement it yourself.");
		}

		public function pmf($x) { return $this->pdf($x); }

		public function cdf($x) {
			throw new BadMethodCallException("CDF not implemented. Please create a pull request if you implement it yourself.");
		}

		public function icdf($y) {
			throw new BadMethodCallException("Inverse CDF not implemented. Please create a pull request if you implement it yourself.");
		}

		public function rand() {
			throw new BadMethodCallException("Random draw not implemented. Please create a pull request if you implement it yourself.");
		}

		// this cannot be defined in the abstract class as it violates strict standards (we always have parameters here, but they're unknown)
		//public static function draw() {
		//	throw new \BadMethodCallException("Static version of random draw not implemented. Please create a pull request if you implement it yourself.");
		//}

		public function rands($n) {
			// generate $n random numbers.
			if(class_exists("SplFixedArray"))
				$return = new SplFixedArray($n);
			else
				$return = array();

			for($i = 0; $i < $n; $i++) {
				$return[$i] = $this->rand();
			}
			return $return;
		}

		public function quantile($y) {
			return $this->icdf($y);
		}

		// $list should be a hash of name=>value. this is used only in error messages.
		protected static function renderParameters($list) {
			$ret = array();
			foreach($list as $k=>$v) {
				$ret[] = "$k = " . var_export($v, true);
			}
			return trim(implode(", ", $ret), ", ");
		}
	}
?>
