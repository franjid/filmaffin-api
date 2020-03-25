<?php

namespace BusinessCase\Film;

interface FilmsIndexBusinessCaseInterface
{
    public const DIC_NAME = 'BusinessCase.Film.FilmsIndexBusinessCaseInterface';

    public function createMapping(): void;
    public function index(array $films): void;
    public function deletePreviousIndexes(): void;
    public function createIndexAlias(): void;
}
