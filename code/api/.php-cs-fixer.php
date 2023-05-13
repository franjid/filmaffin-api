<?php

require __DIR__ . '/vendor/kubawerlos/php-cs-fixer-custom-fixers/bootstrap.php';

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var');

return (new PhpCsFixer\Config())
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRules([
        '@Symfony' => true,
        'yoda_style' => false,
        PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer::name() => [
            'minimum_number_of_parameters' => 3,
        ],
    ])
    ->setFinder($finder);
