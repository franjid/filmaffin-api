<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\ProReview;

class ProReviewCollection
{
    /** @var ProReview[] */
    private readonly array $proReviews;

    public function __construct(ProReview ...$proReviews)
    {
        $this->proReviews = $proReviews;
    }

    public function getItems(): array
    {
        return $this->proReviews;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->proReviews as $proReview) {
            $result[] = $proReview->toArray();
        }

        return $result;
    }
}
