<?php

declare(strict_types=1);

/**
 * Usage check
 */
if ($argc < 2) {
    fwrite(STDERR, "Usage: php phpmetrics-verify.php <metrics.json>\n");
    exit(2);
}

$metricsPath = $argv[1];
$configPath  = __DIR__ . '/../.piqule/phpmetrics.php';

/**
 * File checks
 */
if (!is_file($metricsPath)) {
    fwrite(STDERR, "Metrics file not found: {$metricsPath}\n");
    exit(2);
}

if (!is_file($configPath)) {
    fwrite(STDERR, "PhpMetrics config not found: {$configPath}\n");
    exit(2);
}

/**
 * Load metrics JSON
 */
$json = file_get_contents($metricsPath);
if ($json === false) {
    fwrite(STDERR, "Failed to read metrics file: {$metricsPath}\n");
    exit(2);
}

$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    fwrite(
        STDERR,
        "Invalid JSON in {$metricsPath}: " . json_last_error_msg() . "\n",
    );
    exit(2);
}

if (!is_array($data)) {
    fwrite(STDERR, "Invalid PhpMetrics JSON structure: {$metricsPath}\n");
    exit(2);
}

/**
 * Load config
 */
$config = require $configPath;

if (!is_array($config)) {
    fwrite(STDERR, "Config file must return an array: {$configPath}\n");
    exit(2);
}

$thresholds = $config['thresholds'] ?? [];
$metricsCfg = $config['metrics'] ?? [];

if (!is_array($thresholds) || !is_array($metricsCfg)) {
    fwrite(STDERR, "Invalid PhpMetrics config structure: {$configPath}\n");
    exit(2);
}

/**
 * Unified rule table
 */
$rules = [
    'ccnMethodMax' => [
        'field'    => 'ccnMethodMax',
        'label'    => 'Method CC too high',
        'operator' => '>',
        'limit'    => fn () => $thresholds['ccnMethodMax'] ?? null,
    ],
    'nbMethods' => [
        'field'    => 'nbMethods',
        'label'    => 'Too many methods',
        'operator' => '>',
        'limit'    => fn () => $thresholds['nbMethods'] ?? null,
    ],
    'loc' => [
        'field'    => 'loc',
        'label'    => 'Too many lines',
        'operator' => '>',
        'limit'    => fn () => $thresholds['loc'] ?? null,
    ],
    'efferentCoupling' => [
        'field'    => 'efferentCoupling',
        'label'    => 'Too many dependencies',
        'operator' => '>',
        'limit'    => fn () => $thresholds['efferentCoupling'] ?? null,
    ],
    'maintainabilityIndex' => [
        'field'    => 'maintainabilityIndex',
        'label'    => 'Maintainability index too low',
        'operator' => '<',
        'limit'    => fn () => $metricsCfg['maintainabilityIndex']['min'] ?? null,
    ],
];

$violations = [];

/**
 * Iterate over class metrics
 */
foreach ($data as $className => $metric) {
    if (
        !is_array($metric) ||
        ($metric['_type'] ?? null) !== 'Hal\\Metric\\ClassMetric' ||
        ($metric['interface'] ?? false) ||
        ($metric['abstract'] ?? false)
    ) {
        continue;
    }

    $classErrors = [];

    foreach ($rules as $rule) {
        $limit = $rule['limit']();
        if ($limit === null) {
            continue;
        }

        $value = $metric[$rule['field']] ?? null;
        if (!is_numeric($value)) {
            continue;
        }

        if (
            ($rule['operator'] === '>' && $value > $limit) ||
            ($rule['operator'] === '<' && $value < $limit)
        ) {
            $classErrors[] = sprintf(
                '%s (%d)',
                $rule['label'],
                $value,
            );
        }
    }

    if ($classErrors !== []) {
        $violations[$className] = $classErrors;
    }
}

/**
 * Report
 */
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
