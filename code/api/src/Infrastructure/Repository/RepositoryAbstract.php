<?php

namespace App\Infrastructure\Repository;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class RepositoryAbstract
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getQuery(string $serviceId)
    {
        return $this->container->get($serviceId);
    }
}
