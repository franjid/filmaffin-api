<?php

namespace App\Domain\Helper;

class FilmImageHelper
{
    public static function getImagePosters(int $idFilm): array
    {
        $imagePath ='/' . implode('/', str_split($idFilm, 2)) . '/';

        return [
            'small' => $imagePath . $idFilm . '-msmall.jpg',
            'medium' => $imagePath . $idFilm . '-mmed.jpg',
            'large' => $imagePath . $idFilm . '-large.jpg',
        ];
    }
}
