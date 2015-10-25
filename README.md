Probability Distributions for PHP
=================================

A fully PHP implementation of a number of tools for working with statistical distributions in PHP. 

Version 0.0.1, not ready for use. Untested, incomplete. I will remove this message when 1.0 is finished.

Installation
------------
We'll be on composer shortly. Watch this space.

Supported Distributions
-----------------------
* [Normal](https://en.wikipedia.org/wiki/Normal_distribution)(location μ ∈ R, squared scale σ<sup>2</sup> > 0) plus optional skewness and kurtosis parameters.
* [Binomial](https://en.wikipedia.org/wiki/Binomial_distribution)(n, p)
* [Beta](https://en.wikipedia.org/wiki/Beta_distribution)(shape α > 0, shape β > 0)
* [Gamma](https://en.wikipedia.org/wiki/Gamma_distribution)(shape α > 0, rate β > 0)

All supported distributions are in the namespace gburtini\Distributions and implement the following interface. Implementing new distributions is as easy as extending gburtini\Distributions\Distribution or one of the existing implementations.

Interface
---------
* *Constructor* - takes in the parameters of the distribution and returns an instance.
* public function *pdf*($x) - returns the [density](https://en.wikipedia.org/wiki/Probability_density_function) or [mass](https://en.wikipedia.org/wiki/Probability_mass_function) at a given *discretized* point.
* public function *pmf*($x) - alias for pdf.
* public function *cdf*($x) - returns the cumulative [density](https://en.wikipedia.org/wiki/Probability_density_function) from ![negative infinity](http://www.sciweavers.org/tex2img.php?eq=-%5Cinfty&bc=White&fc=Black&im=jpg&fs=12&ff=arev&edit=) to $x.
* public function *icdf*($y) - inverse CDF function, for a given density, returns a point.
* public function *quantile*($y) - alias for icdf.
* public function *rand*() - draws a sample from this distribution.
* public function *rands*($n) - draws a sample of length $n from this distribution.

Alternatives
------------
There is a [Statistics Functions package](http://php.net/manual/en/ref.stats.php) in PECL called ``stats`` which I have never been able to get to work and has been very quiet since 2006. There is plenty of code for individual distributions around the web, StackOverflow, etc., but in my experience it is hit and miss.

Future Work
-----------
* Implement more univariate distributions. For example, any of: Cauchy, chi-squared, exponential, F, geometric, hypergeometric, Laplace, log-normal, Maxwell–Boltzmann, Pareto, Poisson, Rademacher, Rayleigh, Student's t, uniform, Wakeby, Weibull, Zipf, Zipf-Mandelbrot
* Implement support for multivariate distributions, especially the [multivariate normal](https://en.wikipedia.org/wiki/Multivariate_normal_distribution), but also: Dirchlet (beta), multinomial, etc.
* Generalization of distributions' implementation where appropriate, such as an [elliptical distributions](https://en.wikipedia.org/wiki/Elliptical_distribution) approach to implementing the normal or a categorical distribution implementation of the Bernoulli.
* Design a good interface for alternative parameterizations (for example, [precision-denoted normal](https://en.wikipedia.org/wiki/Normal_distribution#Alternative_parameterizations), mode and concentration denoted beta, and shape and rate denoted gamma).
* Toolkit for performing auxiliary probability-related tasks such as method of moments fitting.
* Add moment-generating and characteristic functions to distributions where they are meaningful and tractable. Generalize concepts like expectation and variance out of them with a clean interface.

Pull Requests
-------------
I will happily merge any new distributions (ideally with tests, but I'm even happy to write the tests), improvements to my code, etc. Please submit a pull request or send me an email.
