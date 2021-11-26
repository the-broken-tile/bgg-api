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
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
    'php_unit_test_class_requires_covers' => false, // PhpCsFixer ignores coversDefaultClass
])
    ->setFinder($finder);
