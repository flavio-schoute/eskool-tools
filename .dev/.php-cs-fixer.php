<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/../')
    ->path([
        '/^app/',
        '/^database/',
    ]);

/** @var PhpCsFixer\Config $config */
$config = include __DIR__ . 'vendor/paqtcom/coding-standards/rules/php-cs-fixer.php';

return $config->setFinder($finder);