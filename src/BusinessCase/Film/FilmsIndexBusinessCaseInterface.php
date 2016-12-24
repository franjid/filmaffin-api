<?php

namespace BusinessCase\Film;

interface FilmsIndexBusinessCaseInterface
{
    const DIC_NAME = 'BusinessCase.Film.FilmsIndexBusinessCaseInterface';

    public function createMapping();
    public function index(array $films);
    public function deletePreviousIndexes();
    public function createIndexAlias();
}
