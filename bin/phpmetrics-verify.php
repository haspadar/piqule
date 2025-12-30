<?php

declare(strict_types=1);

if ($argc < 2) {
    fwrite(STDERR, "Usage: php phpmetrics-verify.php <metrics.json>\n");
    exit(2);
}

$metricsPath = $argv[1];
$configPath = __DIR__ . '/../.piqule/phpmetrics.php';

if (!file_exists($metricsPath)) {
    fwrite(STDERR, "Metrics file not found: {$metricsPath}\n");
    exit(2);
}

if (!file_exists($configPath)) {
    fwrite(STDERR, "PhpMetrics config not found: {$configPath}\n");
    exit(2);
}

$data = json_decode(file_get_contents($metricsPath), true);

if (!is_array($data)) {
    fwrite(STDERR, "Invalid JSON in {$metricsPath}\n");
    exit(2);
}

$config = require $configPath;

$thresholds = $config['thresholds'] ?? [];

$violations = [];

/**
 * phpmetrics --report-json produces a flat map:
 *   key   => metric name (class / package / etc.)
 *   value => metric data with "_type"
 */
foreach ($data as $name => $metric) {
    if (!is_array($metric)) {
        continue;
    }

    // We only validate concrete classes
    if (($metric['_type'] ?? null) !== 'Hal\\Metric\\ClassMetric') {
        continue;
    }

    // Ignore interfaces / abstract classes explicitly
    if (($metric['interface'] ?? false) || ($metric['abstract'] ?? false)) {
        continue;
    }

    $classErrors = [];

    if (isset($thresholds['ccn']) && ($metric['ccn'] ?? 0) > $thresholds['ccn']) {
        $classErrors[] = "CC too high ({$metric['ccn']})";
    }

    if (isset($thresholds['ccnMethodMax']) && ($metric['ccnMethodMax'] ?? 0) > $thresholds['ccnMethodMax']) {
        $classErrors[] = "Method CC too high ({$metric['ccnMethodMax']})";
    }

    if (isset($thresholds['nbMethods']) && ($metric['nbMethods'] ?? 0) > $thresholds['nbMethods']) {
        $classErrors[] = "Too many methods ({$metric['nbMethods']})";
    }

    if (isset($thresholds['loc']) && ($metric['loc'] ?? 0) > $thresholds['loc']) {
        $classErrors[] = "Too many lines ({$metric['loc']})";
    }

    if (isset($thresholds['efferentCoupling']) && ($metric['efferentCoupling'] ?? 0) > $thresholds['efferentCoupling']) {
        $classErrors[] = "Too many dependencies ({$metric['efferentCoupling']})";
    }

    if ($classErrors !== []) {
        $violations[$name] = $classErrors;
    }
}

if ($violations !== []) {
    fwrite(STDERR, "PhpMetrics thresholds violated:\n\n");

    foreach ($violations as $class => $errors) {
        fwrite(STDERR, $class . ":\n");
        foreach ($errors as $error) {
            fwrite(STDERR, "  - {$error}\n");
        }
    }

    exit(1);
}

echo "PhpMetrics thresholds OK\n";
