<?php
namespace gburtini\Distributions;

// TODO: Document or depreciate and remove this file
//
class Bootstrap
{
    // I propose to depreciate this class, It is unclear why it is added
    // TODO: this file is completely unfinished: consider implementing this as a Distribution itself with all the associated methods.
    public static function resampleWithReplacement($data, $n)
    {
        $newData = new \SplFixedArray($n);
        $originalLength = count($data);
        for ($i = 0; $i < $n; $i++) {
            $k = rand(0, $originalLength);
            $newData[$i] = $data[$k];
        }
        return $newData;
    }
}
