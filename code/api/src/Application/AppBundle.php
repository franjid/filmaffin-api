<?php

namespace App\Application;

use App\Application\DependencyInjection\Compiler\LogCompiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new LogCompiler());
    }
}
