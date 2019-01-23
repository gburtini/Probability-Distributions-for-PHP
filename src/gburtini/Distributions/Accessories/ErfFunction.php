<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 22.01.19
 * Time: 23:52
 */

namespace gburtini\Distributions\Accessories;


class ErfFunction
{
    public static function val($x)
    {
        // translation of Press, Teukolsky, Vetterling, Flannery (2001) approximation.
        // https://en.wikipedia.org/wiki/Error_function#Numerical_approximation
        $t = 1 / (1 + 0.5 * abs($x));
        $tau = $t * exp(
                - $x * $x
                - 1.26551223
                + 1.00002368 * $t
                + 0.37409196 * pow($t, 2)
                + 0.09678418 * pow($t, 3)
                - 0.18628806 * pow($t, 4)
                + 0.27886807 * pow($t, 5)
                - 1.13520398 * pow($t, 6)
                + 1.48851587 * pow($t, 7)
                - 0.82215223 * pow($t, 8)
                + 0.17087277 * pow($t, 9)
            );

        if ($x >= 0) {
            return 1 - $tau;
        } else {
            return $tau - 1;
        }
    }
}