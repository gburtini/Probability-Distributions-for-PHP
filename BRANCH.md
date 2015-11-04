This branch contains things that I don't really know should be in (or alternatively, don't know what the interface should look like to maximize utility) the core. For example, sample confidence intervals and bootstrap tools. 

In general, I am open to (even hopeful for) people submitting pull requests to help merge this stuff back in to the master branch.

Functionality
-------------

public function sampleConfidenceInterval($gamma=0.95) - returns a range [L, U] where L is the lower bound of the confidence interval for a distribution with these observations. This is tough to integrate because it's computation strategy is somewhat limited to the normal distribution. A better interface is probably necessary.

