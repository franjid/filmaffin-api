<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\UserReview;

class UserReviewCollection
{
    /** @var UserReview[] */
    private array $userReviews;

    public function __construct(UserReview ...$userReviews)
    {
        $this->userReviews = $userReviews;
    }

    public function getItems(): array
    {
        return $this->userReviews;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->userReviews as $userReview) {
            $result[] = $userReview->toArray();
        }

        return $result;
    }
}
