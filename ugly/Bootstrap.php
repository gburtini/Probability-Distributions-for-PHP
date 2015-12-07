<?php
class GBPDP_Bootstrap {
	  // TODO: this file is completely unfinished: consider implementing this as a Distribution itself with all the associated methods.
	  public static function resampleWithReplacement($data, $n) {
		  $newData = new SplFixedArray($n);
		  $originalLength = count($data);
		  for($i = 0; $i < $n; $i++) { 
			  $k = rand(0, $originalLength);
			  $newData[$i] = $data[$k];
		  }
		  return $newData;
	  }  
  }
?>
