<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 08/01/15
 * Time: 17:24
 */

namespace Cacic\MultiBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ConnectionCompilerClass
 * Copied from http://stackoverflow.com/questions/6409167/symfony-2-multiple-and-dynamic-database-connection
 *
 * @package Cacic\MultiBundle\DependencyInjection
 */
class ConnectionCompilerClass implements CompilerPassInterface {

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $connection = $container
            ->getDefinition('doctrine.dbal.dynamic_connection')
            ->addMethodCall('setSession', [
                new Reference('session')
            ]);
    }
} 