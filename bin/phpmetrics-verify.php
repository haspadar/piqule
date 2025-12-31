<?php

declare(strict_types=1);

/**
 * ---------- helpers ----------
 */
function fail(string $message, int $code = 2): never
{
    fwrite(STDERR, $message . "\n");
    exit($code);
}

function loadJson(string $path): array
{
    if (!is_file($path)) {
        fail("Metrics file not found: {$path}");
    }

    $json = file_get_contents($path);
    if ($json === false) {
        fail("Failed to read metrics file: {$path}");
    }

    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        fail(
            "Invalid JSON in {$path}: " . json_last_error_msg(),
        );
    }

    if (!is_array($data)) {
        fail("Invalid PhpMetrics JSON structure: {$path}");
    }

    return $data;
}

function loadConfig(string $path): array
{
    if (!is_file($path)) {
        fail("PhpMetrics config not found: {$path}");
    }

    $config = require $path;

    if (!is_array($config)) {
        fail("Config file must return an array: {$path}");
    }

    $thresholds = $config['thresholds'] ?? [];
    $metrics    = $config['metrics'] ?? [];

    if (!is_array($thresholds) || !is_array($metrics)) {
        fail("Invalid PhpMetrics config structure: {$path}");
    }

    return [$thresholds, $metrics];
}

function ruleViolated(
    array $metric,
    string $field,
    string $operator,
    int|float $limit
): bool {
    if (!isset($metric[$field]) || !is_numeric($metric[$field])) {
        return false;
    }

    return match ($operator) {
        '>' => $metric[$field] > $limit,
        '<' => $metric[$field] < $limit,
        default => false,
    };
}

/**
 * ---------- entry ----------
 */
if ($argc < 2) {
    fail("Usage: php phpmetrics-verify.php <metrics.json>");
}

$metricsPath = $argv[1];
$configPath  = __DIR__ . '/../.piqule/phpmetrics.php';

$data = loadJson($metricsPath);
[$thresholds, $metricsCfg] = loadConfig($configPath);

/**
 * Unified rule table
 */
$rules = [
    [
        'field' => 'ccnMethodMax',
        'label' => 'Method CC too high',
        'operator' => '>',
        'limit' => $thresholds['ccnMethodMax'] ?? null,
    ],
    [
        'field' => 'nbMethods',
        'label' => 'Too many methods',
        'operator' => '>',
        'limit' => $thresholds['nbMethods'] ?? null,
    ],
    [
        'field' => 'loc',
        'label' => 'Too many lines',
        'operator' => '>',
        'limit' => $thresholds['loc'] ?? null,
    ],
    [
        'field' => 'efferentCoupling',
        'label' => 'Too many dependencies',
        'operator' => '>',
        'limit' => $thresholds['efferentCoupling'] ?? null,
    ],
    [
        'field' => 'maintainabilityIndex',
        'label' => 'Maintainability index too low',
        'operator' => '<',
        'limit' => $metricsCfg['maintainabilityIndex']['min'] ?? null,
    ],
];

$violations = [];

/**
 * ---------- evaluation ----------
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

    $errors = [];

    foreach ($rules as $rule) {
        if ($rule['limit'] === null) {
            continue;
        }

        if (ruleViolated(
            $metric,
            $rule['field'],
            $rule['operator'],
            $rule['limit'],
        )) {
            $errors[] = sprintf(
                '%s (%d)',
                $rule['label'],
                $metric[$rule['field']],
            );
        }
    }

    if ($errors !== []) {
        $violations[$className] = $errors;
    }
}

/**
 * ---------- report ----------
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
