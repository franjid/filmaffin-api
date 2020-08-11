<?php

namespace Tests\Unit\Domain\Helper;

use App\Domain\Helper\FilmImageHelper;
use PHPUnit\Framework\TestCase;

class FilmImageHelperTest extends TestCase
{
    public function testGetImagePosters(): void
    {
        $filmImageHelper = new FilmImageHelper();
        $idFilm = 123456;
        $expectedImagePosters = [
            'small' => '/12/34/56/123456-msmall.jpg',
            'medium' => '/12/34/56/123456-mmed.jpg',
            'large' => '/12/34/56/123456-large.jpg',
        ];

        self::assertEquals($expectedImagePosters, $filmImageHelper->getImagePosters($idFilm));
    }

    public function testGetFramImage(): void
    {
        $filmImageHelper = new FilmImageHelper();
        $idFilm = 654321;
        $expectedFrameImages = [
            'small' => '/65/43/21/frames/0_thumb.jpg',
            'large' => '/65/43/21/frames/0.jpg',
        ];

        self::assertEquals($expectedFrameImages, $filmImageHelper->getFrameImage($idFilm, 0));
    }
}
