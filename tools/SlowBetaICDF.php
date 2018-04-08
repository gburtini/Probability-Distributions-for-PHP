<?php
    require_once dirname(__FILE__) . "/../src/gburtini/Distributions/Beta.php";
    use gburtini\Distributions\Beta;

    $N = 1000;

    $tests = array(
        array(1000, 1000),
        array(1000.5, 1000.5),
        array(1, 1),
        array(1.5, 1.5),
        array(1, 1000),
        array(1, 10000),
        array(1.5, 10000.5)
    );

    foreach ($tests as $test) {
        $beta = new Beta($test[0], $test[1]);
        $a = microtime(true);
        for ($i = 0; $i < $N; $i++) {
            $beta->icdf(0.1);
        }
        echo "Time for $N beta($test[0], $test[1]) icdfs: " . (microtime(true) - $a) . "\n";
    }
