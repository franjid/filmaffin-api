<?php

namespace App\BusinessCase\Film;

interface FilmsIndexBusinessCaseInterface
{
    public function createMapping(): void;
    public function index(array $films): void;
    public function deletePreviousIndexes(): void;
    public function createIndexAlias(): void;
}
