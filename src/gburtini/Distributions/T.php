<?php
/*
* Probability Distributions for PHP - T Distribution
*
* Copyright (C) 2015-2018 Giuseppe Burtini except where otherwise noted.
*
* Other Credits
* -------------
* Some work derived (with permission/license) from jStat - Javascript Statistical Library (MIT licensed).
*/
namespace gburtini\Distributions;

use gburtini\Distributions\Accessories\BetaFunction;
use gburtini\Distributions\Interfaces\DistributionInterface;

class T extends Distribution implements DistributionInterface
{
    protected $degrees;
    public function __construct($dof)
    {
        static::validateParameters($dof);

        $this->degrees = floatval($dof); // float, not integer: http://stats.stackexchange.com/questions/116511/explanation-for-non-integer-degrees-of-freedom-in-t-test-with-unequal-variances
        // TODO: some overflow problems to be dealt with here (large DOF)
    }

    public static function validateParameters($dof)
    {
        if (!is_numeric($dof)) {
            throw new \InvalidArgumentException("Non-numeric parameter in T distribution (" . var_export($dof, true) . ").");
        }
        if ($dof <= 0) {
            throw new \InvalidArgumentException("Parameter (\$dof = " . var_export($dof, true) . " must be positve. ");
        }
    }

    public function pdf($x)
    {
        /* Special cases */

        if ($this->degrees == 1.0) {
            return M_1_PI / (1.0 + $x*$x);
        }

        if ($this->degrees == 2.0) {
            return pow(2.0 + $x*$x, -1.5);
        }

        if ($this->degrees == 3.0) {
            return 6.0 * M_SQRT3 * M_1_PI * pow(3.0 + $x*$x, -2.0);
        }

        /* General case, using the Beta function */

        return (1 / (sqrt($this->degrees) * BetaFunction::betaFunction(0.5, $this->degrees/2))) * pow(1 + (($x*$x)/$this->degrees), -(($this->degrees + 1) / 2));
    }

    public function cdf($x)
    {
        if ($this->degrees == 1.0) {
            return 0.5 + M_1_PI * atan($x);
        }

        if ($this->degrees == 2.0) {
            return 0.5 * (1.0 + $x / sqrt(2.0 + $x*$x));
        }

        /* General case, using the incomplete Beta function */

        $halfDegrees = $this->degrees/2;
        return BetaFunction::incompleteBetaFunction(($x + sqrt($x*$x + $this->degrees)) / (2 * sqrt($x*$x + $this->degrees)), $halfDegrees, $halfDegrees);
    }

    public function icdf($p)
    {
        /* Reimplemented using the algorithm from the Cephes library; same basic idea, but better numerical stability */

        if ($p < 0 || $p > 1) {
            throw new \InvalidArgumentException("Parameter (\$p = " . var_export($p, true) . " must be between 0 and 1. ");
        }

        $k = $this->degrees;

        if ($p > 0.25 && $p < 0.75) {
            if ($p == 0.5) {
                return 0.0; // test extremal case 0.0
            }

            $z = 1.0 - 2.0 * $p;
            $z = BetaFunction::inverseIncompleteBetaFunction(abs($z), 0.5, 0.5*$k);
            $t = sqrt($k * $z / (1.0 - $z));
            if ($p < 0.5) {
                $t = -$t;
            }

            return $t;
        }

        $sign = -1;

        if ($p >= 0.5) {
            $p = 1 - $p;
            $sign = 1;
        }

        $z = BetaFunction::inverseIncompleteBetaFunction(2.0*$p, 0.5*$k, 0.5);

//        I propose to depreciate this line, for such high values php works bad
//        I typed test for this and you can see that inverseIncompleteBetaFunction
//        throws exception for such high values of z, to you should ask about this
//        when you calculating z in line 90.
//        // TODO: pretty sure this is not sensible (overflows!)
//        if ($k > 1e308 * $z) { // should we add test to this? I think that yes if this case is considered
//            return $sign * INF;
//        }

        $t = sqrt($k / $z - $k);
        return $sign * $t;
    }

    /**
     * @return double|double[]
     */
    public function mean()
    {
        return $this->degrees > 1 ? 0 : null;
        // TODO: Implement mean() method.
    }

    /**
     * @return double|double[]
     * 	{\displaystyle \textstyle {\frac {\nu }{\nu -2}}} \textstyle\frac{\nu}{\nu-2} for {\displaystyle \nu >2} \nu > 2, ∞ for {\displaystyle 1<\nu \leq 2} 1 < \nu \le 2,
     * https://en.wikipedia.org/wiki/Student%27s_t-distribution
     */
    public function variance()
    {
        throw new \Exception("INT IMPLEMENTED");
        // TODO: Implement variance() method.
    }

//    /**
//     * @return double|double[]
//     */
//    public function skewness()
//    {
//        // TODO: Implement skewness() method.
//    }
//
//    /**
//     * @return double|double[]
//     */
//    public function kurtosis()
//    {
//        // TODO: Implement kurtosis() method.
//    }
}
