<?php

namespace Cacic\MultiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Cacic\MultiBundle\DependencyInjection\CompilerPass\ConnectionCompilerClass;

class CacicMultiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ConnectionCompilerClass());
    }
}
