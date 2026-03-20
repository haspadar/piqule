<?php

declare(strict_types=1);

/**
 * Piqule configuration
 *
 * For agents: vendor/haspadar/piqule/README.md
 */

use Haspadar\Piqule\Config\DefaultConfig;
use Haspadar\Piqule\Config\OverrideConfig;

return new OverrideConfig(new DefaultConfig(), []);
