<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Collection\FilmAttributeCollection;
use App\Domain\Entity\Collection\FilmFramesCollection;
use App\Domain\Entity\Collection\FilmParticipantCollection;
use App\Domain\Entity\Collection\PlatformCollection;
use App\Domain\Entity\Collection\ProReviewCollection;
use App\Domain\Entity\Collection\UserReviewCollection;
use App\Domain\Helper\FilmImageHelper;

class Film
{
    final public const FIELD_ID_FILM = 'idFilm';
    final public const FIELD_TITLE = 'title';
    final public const FIELD_ORIGINAL_TITLE = 'originalTitle';
    final public const FIELD_RATING = 'rating';
    final public const FIELD_NUM_RATINGS = 'numRatings';
    final public const FIELD_POPULARITY_RANKING = 'popularityRanking';
    final public const FIELD_YEAR = 'year';
    final public const FIELD_DURATION = 'duration';
    final public const FIELD_COUNTRY = 'country';
    final public const FIELD_IN_THEATRES = 'inTheatres';
    final public const FIELD_RELEASE_DATE = 'releaseDate';
    final public const FIELD_SYNOPSIS = 'synopsis';
    final public const FIELD_DIRECTORS = 'directors';
    final public const FIELD_ACTORS = 'actors';
    final public const FIELD_SCREENPLAYERS = 'screenplayers';
    final public const FIELD_MUSICIANS = 'musicians';
    final public const FIELD_CINEMATOGRAPHERS = 'cinematographers';
    final public const FIELD_GENRES = 'genres';
    final public const FIELD_TOPICS = 'topics';
    final public const FIELD_PRO_REVIEWS = 'proReviews';
    final public const FIELD_USER_REVIEWS = 'userReviews';
    final public const FIELD_POSTER_IMAGES = 'posterImages';
    final public const FIELD_NUM_FRAMES = 'numFrames';
    final public const FIELD_FRAMES = 'frames';
    final public const FIELD_PLATFORMS = 'platforms';
    final public const FIELD_NEW_IN_PLATFORM = 'newInPlatform';

    public function __construct(private readonly int $idFilm, private readonly string $title, private readonly string $originalTitle, private readonly ?float $rating, private readonly ?int $numRatings, private readonly ?int $popularityRanking, private readonly int $year, private readonly ?int $duration, private readonly string $country, private readonly bool $inTheatres, private readonly ?string $releaseDate, private readonly ?string $synopsis, private FilmParticipantCollection $directors, private FilmParticipantCollection $actors, private FilmParticipantCollection $screenplayers, private FilmParticipantCollection $musicians, private FilmParticipantCollection $cinematographers, private FilmAttributeCollection $genres, private FilmAttributeCollection $topics, private ProReviewCollection $proReviews, private UserReviewCollection $userReviews, private readonly ?\App\Domain\Entity\PosterImages $posterImages, private readonly int $numFrames, private readonly FilmFramesCollection $frames, private ?\App\Domain\Entity\Collection\PlatformCollection $platforms, private ?string $newInPlatform)
    {
    }

    public function getIdFilm(): int
    {
        return $this->idFilm;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getOriginalTitle(): string
    {
        return $this->originalTitle;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function getNumRatings(): ?int
    {
        return $this->numRatings;
    }

    public function getPopularityRanking(): ?int
    {
        return $this->popularityRanking;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function isInTheatres(): bool
    {
        return $this->inTheatres;
    }

    public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function getDirectors(): FilmParticipantCollection
    {
        return $this->directors;
    }

    public function setDirectors(FilmParticipantCollection $directors): void
    {
        $this->directors = $directors;
    }

    public function getActors(): FilmParticipantCollection
    {
        return $this->actors;
    }

    public function setActors(FilmParticipantCollection $actors): void
    {
        $this->actors = $actors;
    }

    public function getScreenplayers(): FilmParticipantCollection
    {
        return $this->screenplayers;
    }

    public function setScreenplayers(FilmParticipantCollection $screenplayers): void
    {
        $this->screenplayers = $screenplayers;
    }

    public function getMusicians(): FilmParticipantCollection
    {
        return $this->musicians;
    }

    public function setMusicians(FilmParticipantCollection $musicians): void
    {
        $this->musicians = $musicians;
    }

    public function getCinematographers(): FilmParticipantCollection
    {
        return $this->cinematographers;
    }

    public function setCinematographers(FilmParticipantCollection $cinematographers): void
    {
        $this->cinematographers = $cinematographers;
    }

    public function getGenres(): FilmAttributeCollection
    {
        return $this->genres;
    }

    public function setGenres(FilmAttributeCollection $genres): void
    {
        $this->genres = $genres;
    }

    public function getTopics(): FilmAttributeCollection
    {
        return $this->topics;
    }

    public function setTopics(FilmAttributeCollection $topics): void
    {
        $this->topics = $topics;
    }

    public function getProReviews(): ProReviewCollection
    {
        return $this->proReviews;
    }

    public function setProReviews(ProReviewCollection $proReviews): void
    {
        $this->proReviews = $proReviews;
    }

    public function getUserReviews(): UserReviewCollection
    {
        return $this->userReviews;
    }

    public function setUserReviews(UserReviewCollection $userReviews): void
    {
        $this->userReviews = $userReviews;
    }

    public function getPosterImages(): ?PosterImages
    {
        return $this->posterImages;
    }

    public function getNumFrames(): int
    {
        return $this->numFrames;
    }

    public function getFrames(): FilmFramesCollection
    {
        return $this->frames;
    }

    public function getPlatforms(): ?PlatformCollection
    {
        return $this->platforms;
    }

    public function setPlatforms(?PlatformCollection $platforms): void
    {
        $this->platforms = $platforms;
    }

    public function getNewInPlatform(): ?string
    {
        return $this->newInPlatform;
    }

    public function setNewInPlatform(?string $newInPlatform): void
    {
        $this->newInPlatform = $newInPlatform;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_ID_FILM => $this->getIdFilm(),
            self::FIELD_TITLE => $this->getTitle(),
            self::FIELD_ORIGINAL_TITLE => $this->getOriginalTitle(),
            self::FIELD_RATING => $this->getRating(),
            self::FIELD_NUM_RATINGS => $this->getNumRatings(),
            self::FIELD_POPULARITY_RANKING => $this->getPopularityRanking(),
            self::FIELD_YEAR => $this->getYear(),
            self::FIELD_DURATION => $this->getDuration(),
            self::FIELD_COUNTRY => $this->getCountry(),
            self::FIELD_IN_THEATRES => $this->isInTheatres(),
            self::FIELD_RELEASE_DATE => $this->getReleaseDate(),
            self::FIELD_SYNOPSIS => $this->getSynopsis(),
            self::FIELD_DIRECTORS => $this->getDirectors()->toArray(),
            self::FIELD_ACTORS => $this->getActors()->toArray(),
            self::FIELD_SCREENPLAYERS => $this->getScreenplayers()->toArray(),
            self::FIELD_MUSICIANS => $this->getMusicians()->toArray(),
            self::FIELD_CINEMATOGRAPHERS => $this->getCinematographers()->toArray(),
            self::FIELD_GENRES => $this->getGenres()->toArray(),
            self::FIELD_TOPICS => $this->getTopics()->toArray(),
            self::FIELD_PRO_REVIEWS => $this->getProReviews()->toArray(),
            self::FIELD_USER_REVIEWS => $this->getUserReviews()->toArray(),
            self::FIELD_POSTER_IMAGES => $this->getPosterImages() ? $this->getPosterImages()->toArray() : null,
            self::FIELD_NUM_FRAMES => $this->getNumFrames(),
            self::FIELD_FRAMES => $this->getFrames()->toArray(),
            self::FIELD_PLATFORMS => $this->getPlatforms()->toArray(),
            self::FIELD_NEW_IN_PLATFORM => $this->getNewInPlatform(),
        ];
    }

    public static function buildFromArray(array $data): self
    {
        $directors = isset($data[self::FIELD_DIRECTORS]) && is_array($data[self::FIELD_DIRECTORS])
            ? new FilmParticipantCollection(
                ...array_map(static fn($name) => new FilmParticipant($name), $data[self::FIELD_DIRECTORS])
            )
            : new FilmParticipantCollection();

        $actors = isset($data[self::FIELD_ACTORS]) && is_array($data[self::FIELD_ACTORS])
            ? new FilmParticipantCollection(
                ...array_map(static fn($name) => new FilmParticipant($name), $data[self::FIELD_ACTORS])
            )
            : new FilmParticipantCollection();

        $screenplayers = isset($data[self::FIELD_SCREENPLAYERS]) && is_array($data[self::FIELD_SCREENPLAYERS])
            ? new FilmParticipantCollection(
                ...array_map(static fn($name) => new FilmParticipant($name), $data[self::FIELD_SCREENPLAYERS])
            )
            : new FilmParticipantCollection();

        $musicians = isset($data[self::FIELD_MUSICIANS]) && is_array($data[self::FIELD_MUSICIANS])
            ? new FilmParticipantCollection(
                ...array_map(static fn($name) => new FilmParticipant($name), $data[self::FIELD_MUSICIANS])
            )
            : new FilmParticipantCollection();

        $cinematographers = isset($data[self::FIELD_CINEMATOGRAPHERS]) && is_array($data[self::FIELD_CINEMATOGRAPHERS])
            ? new FilmParticipantCollection(
                ...array_map(static fn($name) => new FilmParticipant($name), $data[self::FIELD_CINEMATOGRAPHERS])
            )
            : new FilmParticipantCollection();

        $genres = isset($data[self::FIELD_GENRES]) && is_array($data[self::FIELD_GENRES])
            ? new FilmAttributeCollection(
                ...array_map(static fn($name) => new FilmAttribute($name), $data[self::FIELD_GENRES])
            )
            : new FilmAttributeCollection();

        $topics = isset($data[self::FIELD_TOPICS]) && is_array($data[self::FIELD_TOPICS])
            ? new FilmAttributeCollection(
                ...array_map(static fn($name) => new FilmAttribute($name), $data[self::FIELD_TOPICS])
            )
            : new FilmAttributeCollection();

        $proReviews = new ProReviewCollection();
        if (isset($data[self::FIELD_PRO_REVIEWS])) {
            if (is_array($data[self::FIELD_PRO_REVIEWS])) {
                $proReviews = new ProReviewCollection(
                    ...array_map(
                        static fn($proReview) => ProReview::buildFromArray($proReview), $data[self::FIELD_PRO_REVIEWS]
                    )
                );
            } else {
                try {
                    $proReviews = isset($data[self::FIELD_PRO_REVIEWS])
                        ? new ProReviewCollection(
                            ...array_map(
                                static fn($proReview) => ProReview::buildFromArray($proReview), json_decode((string) $data[self::FIELD_PRO_REVIEWS], true, 512, JSON_THROW_ON_ERROR)
                            )
                        )
                        : new ProReviewCollection();
                } catch (\JsonException) {
                    $proReviews = new ProReviewCollection();
                }
            }
        }

        $userReviews = isset($data[self::FIELD_USER_REVIEWS]) && is_array($data[self::FIELD_USER_REVIEWS])
            ? new UserReviewCollection(
                ...array_map(
                    static fn($userReview) => UserReview::buildFromArray($userReview), $data[self::FIELD_USER_REVIEWS]
                ))
            : new UserReviewCollection();

        $frames = new FilmFramesCollection();
        if (isset($data[self::FIELD_NUM_FRAMES]) && $data[self::FIELD_NUM_FRAMES] > 0) {
            $filmImageHelper = new FilmImageHelper();
            $frames = [];

            for ($i = 0; $i < $data[self::FIELD_NUM_FRAMES]; ++$i) {
                $frames[] = FilmFrame::buildFromArray($filmImageHelper->getFrameImage($data[self::FIELD_ID_FILM], $i));
            }

            $frames = new FilmFramesCollection(...$frames);
        }

        $platforms = isset($data[self::FIELD_PLATFORMS]) && is_array($data[self::FIELD_PLATFORMS])
            ? new PlatformCollection(
                ...array_map(
                    static fn($platform) => PlatformAvailability::buildFromArray($platform), $data[self::FIELD_PLATFORMS]
                ))
            : new PlatformCollection();

        return new self(
            $data[self::FIELD_ID_FILM],
            $data[self::FIELD_TITLE],
            $data[self::FIELD_ORIGINAL_TITLE],
            $data[self::FIELD_RATING] ?? null,
            $data[self::FIELD_NUM_RATINGS] ?? null,
            $data[self::FIELD_POPULARITY_RANKING] ?? null,
            $data[self::FIELD_YEAR] ?? null,
            $data[self::FIELD_DURATION] ?? null,
            $data[self::FIELD_COUNTRY],
            isset($data[self::FIELD_IN_THEATRES]) ? (bool) $data[self::FIELD_IN_THEATRES] : false,
            $data[self::FIELD_RELEASE_DATE] ?? null,
            $data[self::FIELD_SYNOPSIS] ?? null,
            $directors,
            $actors,
            $screenplayers,
            $musicians,
            $cinematographers,
            $genres,
            $topics,
            $proReviews,
            $userReviews,
            isset($data[self::FIELD_POSTER_IMAGES]) ? PosterImages::buildFromArray($data[self::FIELD_POSTER_IMAGES]) : null,
            $data[self::FIELD_NUM_FRAMES] ?? 0,
            $frames,
            $platforms,
            $data[self::FIELD_NEW_IN_PLATFORM] ?? null,
        );
    }
}
