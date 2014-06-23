<?php
require_once __DIR__ . '/vendor/autoload.php';

use Example\Order\Process\Postpayment;
use Example\Order\Process\Prepayment;
use Metabor\NamedCollection;
use Metabor\Statemachine\Graph\Graph;
use Fhaculty\Graph\GraphViz;
try {
    $processes = new NamedCollection();
    $processes->add(new Prepayment());
    $processes->add(new Postpayment());

    if (isset($_GET['process'])) {
        $processName = strtolower($_GET['process']);
        if ($processes->has($processName)) {
            $process = $processes->get($processName);
        }
    }
    if (!$process) {
        $processName = reset($processes->getNames());
        $process = $processes->get($processName);
    }

    $graph = new Graph();
    $graph->addStateCollection($process);

    $viz = new GraphViz($graph);
    $viz->setFormat('svg');
    echo file_get_contents($viz->createImageFile());
} catch (Exception $e) {
    echo $e->getMessage();
}
