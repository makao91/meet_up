<?php

use PhpCsFixer\Finder;

$rules = [
    '@PSR1' => true,
    '@PSR2' => true,
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    '@DoctrineAnnotation' => true,

    // https://mlocati.github.io/php-cs-fixer-configurator/#version:3.11|fixer:ordered_imports
    'ordered_imports' => [
        'sort_algorithm' => 'length',
    ],

    // https://mlocati.github.io/php-cs-fixer-configurator/#version:3.11|fixer:ordered_class_elements
    'ordered_class_elements' => false,

    // https://mlocati.github.io/php-cs-fixer-configurator/#version:3.11|fixer:single_line_comment_style
    'single_line_comment_style' => [
        'comment_types' => [
            'hash',
        ],
    ],

    // https://mlocati.github.io/php-cs-fixer-configurator/#version:3.11|fixer:php_unit_method_casing
    'php_unit_method_casing' => false,

    // https://mlocati.github.io/php-cs-fixer-configurator/#version:3.11|fixer:single_quote
    'single_quote' => [
        'strings_containing_single_quote_chars' => false,
    ],
    // https://mlocati.github.io/php-cs-fixer-configurator/#version:3.11|fixer:return_assignment
    'return_assignment' => false,

    // https://mlocati.github.io/php-cs-fixer-configurator/#version:3.11|fixer:phpdoc_add_missing_param_annotation
    'phpdoc_add_missing_param_annotation' => false,

    // https://mlocati.github.io/php-cs-fixer-configurator/#version:3.11|fixer:php_unit_internal_class
    'php_unit_internal_class' => false,

    // https://mlocati.github.io/php-cs-fixer-configurator/#version:3.11|fixer:php_unit_test_class_requires_covers
    'php_unit_test_class_requires_covers' => false,

    // https://mlocati.github.io/php-cs-fixer-configurator/#version:3.11|fixer:phpdoc_separation
    'phpdoc_separation' => false,
];

$project_path = getcwd();
$domains_path = $project_path.'/../';

$finder = Finder::create()
    ->in([
        $project_path.'/app',
        $project_path.'/config',
        $project_path.'/database',
        $project_path.'/resources',
        $project_path.'/routes',
        $project_path.'/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

$config = new PhpCsFixer\Config();
$config
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setCacheFile('.php-cs-fixer.cache')
    ->setFinder($finder)
;

return $config;
