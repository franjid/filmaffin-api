<?php

namespace Repository;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class RepositoryAbstract
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $serviceId
     * @return object
     */
    protected function getQuery($serviceId)
    {
        return $this->container->get($serviceId);
    }
}
