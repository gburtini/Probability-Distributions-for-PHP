<?php
/*
* Statistical Distributions for PHP - Dirchlet Distribution
*
* Copyright (C) 2015-2018 Giuseppe Burtini.
*/
namespace gburtini\Distributions;

class Dirichlet extends Distribution
{
    protected $k;
    protected $alpha;
    protected $gammaDistributions;
    public function __construct(array $concentration, $number = null)
    {
        if (!is_array($concentration)) {
            if ($number !== null) {
                $concentration = array_fill_keys(range(0, $number-1), $concentration);
            } else {
                throw new \InvalidArgumentException("Concentration wasn't a valid array and number not specified. Creating a dirichlet is impossible.");
            }
        }

        $this->alpha = $concentration;
    }

    public function rand()
    {
        return self::draw($this->k, $this->alpha);
    }

    public static function draw($alpha, $k = null)
    {
        $draws = array();
        foreach ($alpha as $g) {
            $draws[] = Gamma::draw($g, 1);
        }

        $sum = array_sum($draws);

        return array_map(
            function ($draws) use ($sum) {
                return $draws / $sum;
            },
            $draws,
            array_fill(0, count($draws), $sum)
        );
    }
}
