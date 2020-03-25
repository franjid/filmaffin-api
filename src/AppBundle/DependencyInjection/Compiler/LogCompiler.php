<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LogCompiler implements CompilerPassInterface
{
    /**
     * Process
     *
     * Method setLogger come from Component\Log\LogTrait.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('logger');

        foreach ($taggedServices as $serviceId => $tagAttributes)
        {
            if (!isset($tagAttributes[0]['channel'])) {
                throw new \RuntimeException('If you put a logger tag you need to add an attribute channel as well');
            }

            $definition = $container->getDefinition($serviceId);
            $nameChannel = $tagAttributes[0]['channel'];
            $nameLoggerChannelService = 'monolog.logger.' . $nameChannel;
            $definition->addMethodCall('setLogger', [new Reference($nameLoggerChannelService)]);
        }
    }
}
