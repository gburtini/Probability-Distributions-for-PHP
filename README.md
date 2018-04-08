# Probability Distributions for PHP

[![Build Status](https://travis-ci.org/gburtini/Probability-Distributions-for-PHP.svg)](https://travis-ci.org/gburtini/Probability-Distributions-for-PHP.svg)

A userland PHP implementation of a number of tools for working with statistical distributions in PHP.

**Compatibility**: PHP 5.4 and above. Tested and supported on `5.4` through `7.1` as well as `nightly`. We do _not_ currently support `hhvm`.

## Installation

This package is available in Packagist/Composer as `gburtini/distributions`. For noncomposer uses, clone the repository and require files directly.

## Supported Distributions

The name given here is the name of the class.

* [Normal](https://en.wikipedia.org/wiki/Normal_distribution)(location μ ∈ R, squared scale σ<sup>2</sup> > 0)
* [Binomial](https://en.wikipedia.org/wiki/Binomial_distribution)(number of trials, probability of success per trial in [0,1])
* [Bernoulli](https://en.wikipedia.org/wiki/Bernoulli_distribution)(fraction in [0,1])
* [Beta](https://en.wikipedia.org/wiki/Beta_distribution)(shape α > 0, shape β > 0)
* [Gamma](https://en.wikipedia.org/wiki/Gamma_distribution)(shape α > 0, rate β > 0)
* [T](https://en.wikipedia.org/wiki/Student's_t_distribution)(degrees of freedom v > 0)
* [Dirichlet](https://en.wikipedia.org/wiki/Dirichlet_distribution)(array of concentration parameters α > 0)
* [Poisson](https://en.wikipedia.org/wiki/Poisson_distribution)(mean λ > 0)

All supported distributions are in the namespace `gburtini\Distributions` and implement the following interface. Implementing new distributions is as easy as extending `gburtini\Distributions\Distribution` or one of the existing implementations.

## Interface

* _Constructor_ - takes in the parameters of the distribution and returns an instance.
* public function _pdf_($x) - returns the [density](https://en.wikipedia.org/wiki/Probability_density_function) or [mass](https://en.wikipedia.org/wiki/Probability_mass_function) at a given _discretized_ point.
* public function _pmf_($x) - alias for pdf.
* public function _cdf_($x) - returns the cumulatfive [density](https://en.wikipedia.org/wiki/Probability_density_function) from -∞ to $x.
* public function _icdf_($y) - inverse CDF function, for a given density, returns a point.
* public function _quantile_($y) - alias for icdf.
* public function _rand_() - draws a sample from this distribution.
* public function _rands_($n) - draws a sample of length $n from this distribution.
* public _static_ function _draw_(...) - draws a sample from the distribution given by the parameters passed in, a static alternative to rand.

## Namespaces

`gburtini\Distributions` contains the distribution classes as indicated above.
`gburtini\Distributions\Accessories` contains BetaFunction and GammaFunction, two classes containing accessory functions for computing complete, incomplete and inverse beta and gamma functions numerically.

## Example

Examples are provided in a comment at the top of most of the implementation files. In general, you should be able to use the parametrization listed above under "Supported Distributions" to create classes that implement the methods under "Interfaces".

```php
use gburtini\Distributions\Beta;
$beta = new Beta(1, 100);
$draw = $beta->rand();
if($draw > 0.5) {
  echo "We drew a number bigger than 0.5 from a Beta(1,100).\n";
}

// $beta->pdf($x) = [0,1]
// $beta->cdf($x) = [0,1] non-decreasing
// $beta::quantile($y in [0,1]) = [0,1] (aliased Beta::icdf)
// $beta->rand() = [0,1]
```

## Alternatives

There is a [Statistics Functions package](http://php.net/manual/en/ref.stats.php) in PECL called `stats` which I have never been able to get to work and has been very quiet since 2006. There is plenty of code for individual distributions around the web, StackOverflow, etc., but in my experience it is hit and miss. To whatever extent possible, I would be happy to (but have not yet) wrap the stats\_ functions (if `function_exists`) where they have functionality that this package does not.

## Future Work

* First, implement the interface for all distributions!
* Add mean, median, mode, variance calculators.
* Implement more univariate distributions. For example, any of: Cauchy, chi-squared, exponential, F, geometric, hypergeometric, Laplace, log-normal, Maxwell–Boltzmann, Pareto, Rademacher, Rayleigh, uniform, Wakeby, Weibull, Zipf, Zipf-Mandelbrot. Producing more distributions may be aided by the [cool relational diagram](http://www.johndcook.com/blog/distribution_chart/) on John D. Cook's website.
* Implement support for multivariate distributions, especially the [multivariate normal](https://en.wikipedia.org/wiki/Multivariate_normal_distribution), but also: multinomial, etc.
* Generalization of distributions' implementation where appropriate, such as an [elliptical distributions](https://en.wikipedia.org/wiki/Elliptical_distribution) approach to implementing the normal or a categorical distribution implementation of the Bernoulli.
* Design a good interface for alternative parameterizations (for example, [precision-denoted normal](https://en.wikipedia.org/wiki/Normal_distribution#Alternative_parameterizations), mode and concentration denoted beta, and shape and rate denoted gamma).
* Toolkit for performing auxiliary probability-related tasks such as method of moments fitting.
* Add moment-generating and characteristic functions to distributions where they are meaningful and tractable. Generalize concepts like expectation and variance out of them with a clean interface.

## Pull Requests

I will happily merge any new distributions (ideally with tests, but I'm even happy to write the tests), improvements to my code, etc. Please submit a pull request or send me an email.

## License

[MIT licensed](https://tldrlegal.com/license/mit-license). Please contact me if this does not work for your use-case.
