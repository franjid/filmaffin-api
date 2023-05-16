<?php

namespace App\Domain\Interfaces;

use App\Domain\Entity\Collection\FilmCollection;

interface FilmsIndexerInterface
{
    public function createMapping(): void;

    public function index(FilmCollection $films): void;

    public function deletePreviousIndexes(): void;

    public function createIndexAlias(): void;

    public function getLastIndexName(): string;

    public function setCurrentIndexName(string $indexName): void;
}
