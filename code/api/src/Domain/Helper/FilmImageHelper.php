<?php

namespace App\Domain\Helper;

class FilmImageHelper
{
    public function getImagePosters(int $idFilm): array
    {
        $imagePath = $this->getFilmImagesPath($idFilm);

        return [
            'small' => $imagePath.$idFilm.'-msmall.jpg',
            'medium' => $imagePath.$idFilm.'-mmed.jpg',
            'large' => $imagePath.$idFilm.'-large.jpg',
        ];
    }

    public function getFrameImage(int $idFilm, int $numFrame): array
    {
        $imagePath = $this->getFilmImagesPath($idFilm).'frames/';

        return [
            'small' => $imagePath.$numFrame.'_thumb.jpg',
            'large' => $imagePath.$numFrame.'.jpg',
        ];
    }

    private function getFilmImagesPath(int $idFilm): string
    {
        return '/'.implode('/', str_split($idFilm, 2)).'/';
    }
}
