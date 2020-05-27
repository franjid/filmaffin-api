<?php

namespace App\Domain\Interfaces;

interface FilmsIndexerInterface
{
    public function createMapping(): void;

    public function index(array $films): void;

    public function deletePreviousIndexes(): void;

    public function createIndexAlias(): void;
}
