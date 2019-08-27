<?php

namespace gburtini\Distributions;

class BinomialCI extends Binomial
{
    public function jeffreys($confidence = 0.95)
    {
        // this is a Bayesian derivation with great frequentist properties, even around 0 and 1 success rates.

        if ($confidence > 1 || $confidence < 0) {
            throw new \InvalidArgumentException("p-value must be between 0 and 1.");
        }

        $a = ($this->n * $this->p);
        $b = ($this->n * (1-$this->p));
        $beta = new Beta(0.5 + $a, 0.5 + $b);

        if ($a > 0) {
            $lower = $beta->icdf(1-($confidence));
        } else {
            $lower = 0;
        }

        if ($a < $this->n) {
            $upper = $beta->icdf(($confidence));
        } else {
            $upper = 1;
        }

        return array(
            "lower" => $lower,
            "upper" => $upper
        );
    }
}
