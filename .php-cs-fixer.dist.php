<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR1' => true,
    '@PSR12' => true,
    '@PSR12:risky' => true,
    '@DoctrineAnnotation' => true,
    '@PHP80Migration' => true,
    '@PHP80Migration:risky' => true,
    '@PHP81Migration' => true,
    '@PhpCsFixer' => true,
    '@PhpCsFixer:risky' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
    'php_unit_test_class_requires_covers' => false, // PhpCsFixer ignores coversDefaultClass
    'php_unit_test_case_static_method_calls' => [
        'call_type' => 'self',
    ],
    'php_unit_strict' => false, // DTOs can be compared non-strictly.
])
    ->setFinder($finder);
