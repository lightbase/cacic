<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 27/08/15
 * Time: 10:59
 */

namespace Cacic\MultiBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Provides support for environment specific fixtures.
 *
 * This container aware, abstract data fixture is used to only allow loading in
 * specific environments. The environments the data fixture will be loaded in is
 * determined by the list of environment names returned by `getEnvironments()`.
 *
 * > The fixture will still be shown as having been loaded by the Doctrine
 * > command, `doctrine:fixtures:load`, despite not having been actually
 * > loaded.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 *
 * Fonte: http://stackoverflow.com/questions/11817971/environment-specific-data-fixtures-with-symfonydoctrine
 */
abstract class AbstractDataFixture extends AbstractFixture implements ContainerAwareInterface, FixtureInterface, OrderedFixtureInterface
{
    /**
     * The dependency injection container.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->container->get('kernel');

        if (in_array($kernel->getEnvironment(), $this->getEnvironments())) {
            $this->doLoad($manager);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Performs the actual fixtures loading.
     *
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     *
     * @param ObjectManager $manager The object manager.
     */
    abstract protected function doLoad(ObjectManager $manager);

    /**
     * Returns the environments the fixtures may be loaded in.
     *
     * @return array The name of the environments.
     */
    abstract protected function getEnvironments();
}