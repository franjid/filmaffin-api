<?php

namespace App\Infrastructure\Repository;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class RepositoryAbstract
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    protected function getQuery(string $serviceId)
    {
        return $this->container->get($serviceId);
    }
}
