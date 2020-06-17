<?php

namespace App\Domain\Entity\Collection;

use App\Domain\Entity\ProReview;

class ProReviewCollection
{
    /** @var ProReview[] $proReviews */
    private array $proReviews;

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
