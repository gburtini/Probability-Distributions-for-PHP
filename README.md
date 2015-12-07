Probability Distributions for PHP
=================================

A fully PHP implementation of a number of tools for working with statistical distributions in PHP. Currently compatible with PHP 5.2+ (at least -- possibly as little as PHP 5.0, but currently untested).  

Installation
------------
This package is available in Packagist/Composer as ``gburtini/distributions``.


Supported Distributions
-----------------------
The name given here is the name of the class. 

* [Normal](https://en.wikipedia.org/wiki/Normal_distribution)(location μ ∈ R, squared scale σ<sup>2</sup> > 0) 
* [Binomial](https://en.wikipedia.org/wiki/Binomial_distribution)(fraction in [0,1])
* [Beta](https://en.wikipedia.org/wiki/Beta_distribution)(shape α > 0, shape β > 0)
* [Gamma](https://en.wikipedia.org/wiki/Gamma_distribution)(shape α > 0, rate β > 0)
* [T](https://en.wikipedia.org/wiki/Student's_t_distribution)(degrees of freedom v > 0)
* [Dirichlet](https://en.wikipedia.org/wiki/Dirichlet_distribution)(array of concentration parameters α > 0)

All supported distributions are in the namespace ``gburtini\Distributions`` and implement the following interface. Implementing new distributions is as easy as extending ``gburtini\Distributions\Distribution`` or one of the existing implementations.

Interface
---------
* *Constructor* - takes in the parameters of the distribution and returns an instance.
* public function *pdf*($x) - returns the [density](https://en.wikipedia.org/wiki/Probability_density_function) or [mass](https://en.wikipedia.org/wiki/Probability_mass_function) at a given *discretized* point.
* public function *pmf*($x) - alias for pdf.
* public function *cdf*($x) - returns the cumulative [density](https://en.wikipedia.org/wiki/Probability_density_function) from -∞ to $x.
* public function *icdf*($y) - inverse CDF function, for a given density, returns a point.
* public function *quantile*($y) - alias for icdf.
* public function *rand*() - draws a sample from this distribution.
* public function *rands*($n) - draws a sample of length $n from this distribution.
* public *static* function *draw*(...) - draws a sample from the distribution given by the parameters passed in, a static alternative to rand.

Namespaces
----------
``gburtini\Distributions`` contains the distribution classes as indicated above. 
``gburtini\Distributions\Accessories`` contains BetaFunction and GammaFunction, two classes containing accessory functions for computing complete, incomplete and inverse beta and gamma functions numerically. 

If you are using a version of PHP pre-namespaces, the intent in this branch is for the ``ugly/`` directory to implement pseudonamespace ("ugly") names, with the prefix GBPDP_.

Alternatives
------------
There is a [Statistics Functions package](http://php.net/manual/en/ref.stats.php) in PECL called ``stats`` which I have never been able to get to work and has been very quiet since 2006. There is plenty of code for individual distributions around the web, StackOverflow, etc., but in my experience it is hit and miss.

Future Work
-----------
* First, implement the interface for all distributions!
* Add mean, median, mode, variance calculators.
* Implement more univariate distributions. For example, any of: Cauchy, chi-squared, exponential, F, geometric, hypergeometric, Laplace, log-normal, Maxwell–Boltzmann, Pareto, Poisson, Rademacher, Rayleigh, uniform, Wakeby, Weibull, Zipf, Zipf-Mandelbrot. Producing more distributions may be aided by the [cool relational diagram](http://www.johndcook.com/blog/distribution_chart/) on John D. Cook's website.
* Implement support for multivariate distributions, especially the [multivariate normal](https://en.wikipedia.org/wiki/Multivariate_normal_distribution), but also: multinomial, etc.
* Generalization of distributions' implementation where appropriate, such as an [elliptical distributions](https://en.wikipedia.org/wiki/Elliptical_distribution) approach to implementing the normal or a categorical distribution implementation of the Bernoulli.
* Design a good interface for alternative parameterizations (for example, [precision-denoted normal](https://en.wikipedia.org/wiki/Normal_distribution#Alternative_parameterizations), mode and concentration denoted beta, and shape and rate denoted gamma).
* Toolkit for performing auxiliary probability-related tasks such as method of moments fitting.
* Add moment-generating and characteristic functions to distributions where they are meaningful and tractable. Generalize concepts like expectation and variance out of them with a clean interface.

Pull Requests
-------------
I will happily merge any new distributions (ideally with tests, but I'm even happy to write the tests), improvements to my code, etc. Please submit a pull request or send me an email. This branch currently insists on PHP 5.2 compatibility.

