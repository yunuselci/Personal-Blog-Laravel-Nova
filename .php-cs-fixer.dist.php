<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/tests')
    ->in(__DIR__ . '/database')
    ->in(__DIR__ . '/config')
    ->in(__DIR__ . '/routes');

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],

        '@PHP71Migration:risky' => true,
        'declare_strict_types' => false,
        '@PHPUnit75Migration:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'general_phpdoc_annotation_remove' => ['annotations' => ['expectedDeprecation']],
    ])
    ->setFinder($finder);
return $config;
