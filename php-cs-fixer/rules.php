<?php

declare(strict_types=1);

/*
 * SPDX-FileCopyrightText: 2025 Konstantinas Mesnikas
 * SPDX-License-Identifier: MIT
 */

$currentYear = (int) \date('Y');
$startYear = 2025;

$header = $currentYear === $startYear
    ? "SPDX-FileCopyrightText: $startYear Konstantinas Mesnikas\nSPDX-License-Identifier: MIT"
    : "SPDX-FileCopyrightText: $startYear-$currentYear Konstantinas Mesnikas\nSPDX-License-Identifier: MIT";

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        'header_comment' => [
            'header' => $header,
            'comment_type' => 'comment',
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ],

        '@PER-CS2.0' => true,
        '@PHP82Migration' => true,

        // Arrays
        'array_syntax' => ['syntax' => 'short'],
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],

        // Imports
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const'],
        ],
        'no_unused_imports' => true,
        'no_leading_import_slash' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],

        // Strict types
        'declare_strict_types' => true,

        // Final & visibility
        'final_class' => true,
        'final_internal_class' => true,

        // Types
        'fully_qualified_strict_types' => true,
        'native_type_declaration_casing' => true,

        // Formatting
        'blank_line_before_statement' => ['statements' => ['return', 'throw', 'try']],
        'class_attributes_separation' => [
            'elements' => ['method' => 'one', 'property' => 'one'],
        ],
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'no_extra_blank_lines' => ['tokens' => ['extra', 'throw', 'use']],
        'no_trailing_comma_in_singleline' => true,
        'no_whitespace_in_blank_line' => true,

        // PHPDoc
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_indent' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_order' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,

        // Clean code
        'strict_comparison' => true,
        'strict_param' => true,

        // Casting
        'cast_spaces' => ['space' => 'single'],
        'lowercase_cast' => true,

        // Control structures
        'control_structure_braces' => true,
        'control_structure_continuation_position' => true,

        // Operators
        'binary_operator_spaces' => ['default' => 'single_space'],
        'concat_space' => ['spacing' => 'one'],
        'unary_operator_spaces' => true,
        'not_operator_with_successor_space' => true,

        // Misc
        'encoding' => true,
        'full_opening_tag' => true,
        'single_quote' => true,
        'ternary_operator_spaces' => true,
    ])
    ->setUnsupportedPhpVersionAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/..'),
    );
