<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 27/08/15
 * Time: 10:33
 */

namespace Cacic\MultiBundle\Helper;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\ProxyReferenceRepository;
use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;

/**
 * Classe de execução dos Fixtures do Doctrine
 * Copiado de LiipFunctionalTestBundle
 *
 * Class FixturesHelper
 * @package Cacic\MultiBundle\Helper
 */
class FixturesHelper
{
    public function __construct() {
        // Fixtures to be loaded
        $this->classes = array(
            'Cacic\CommonBundle\DataFixtures\ORM\LoadLocalData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadRedeData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadClasseData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadClassPropertyData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadAcaoData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadCollectDefClassData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadConfiguracaoPadraoData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadConfiguracaoLocalData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadGrupoUsuarioData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadUsuarioData',
            'Cacic\WSBundle\DataFixtures\ORM\LoadRedeVersaoModuloData',
            'Cacic\WSBundle\DataFixtures\ORM\LoadTipoSo'
        );
    }

    /**
     * Retrieve Doctrine DataFixtures loader.
     *
     * @param ContainerInterface $container
     * @param array $classNames
     *
     * @return Loader
     */
    protected function getFixtureLoader(ContainerInterface $container, array $classNames)
    {
        $loaderClass = class_exists('Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader')
            ? 'Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader'
            : (class_exists('Doctrine\Bundle\FixturesBundle\Common\DataFixtures\Loader')
                ? 'Doctrine\Bundle\FixturesBundle\Common\DataFixtures\Loader'
                : 'Symfony\Bundle\DoctrineFixturesBundle\Common\DataFixtures\Loader');

        $loader = new $loaderClass($container);

        foreach ($classNames as $className) {
            $this->loadFixtureClass($loader, $className);
        }

        return $loader;
    }

    /**
     * Load a data fixture class.
     *
     * @param Loader $loader
     * @param string $className
     */
    protected function loadFixtureClass(Loader $loader, $className)
    {
        $fixture = new $className();

        if ($loader->hasFixture($fixture)) {
            unset($fixture);
            return;
        }

        $loader->addFixture($fixture);

        if ($fixture instanceof DependentFixtureInterface) {
            foreach ($fixture->getDependencies() as $dependency) {
                $this->loadFixtureClass($loader, $dependency);
            }
        }
    }

    public function loadFixtures($type, ObjectManager $om, ContainerInterface $container) {
        $executorClass = 'PHPCR' === $type && class_exists('Doctrine\Bundle\PHPCRBundle\DataFixtures\PHPCRExecutor')
            ? 'Doctrine\Bundle\PHPCRBundle\DataFixtures\PHPCRExecutor'
            : 'Doctrine\\Common\\DataFixtures\\Executor\\'.$type.'Executor';
        $referenceRepository = new ProxyReferenceRepository($om);
        $cacheDriver = $om->getMetadataFactory()->getCacheDriver();

        if ($cacheDriver) {
            $cacheDriver->deleteAll();
        }

        if ('ORM' === $type) {
            $connection = $om->getConnection();
        }

        $purgerClass = 'Doctrine\\Common\\DataFixtures\\Purger\\'.$type.'Purger';
        if ('PHPCR' === $type) {
            $purger = new $purgerClass($om);
            $initManager = $container->has('doctrine_phpcr.initializer_manager')
                ? $container->get('doctrine_phpcr.initializer_manager')
                : null;

            $executor = new $executorClass($om, $purger, $initManager);
        } else {
            $purger = new $purgerClass();
            $executor = new $executorClass($om, $purger);
        }

        $executor->setReferenceRepository($referenceRepository);
        $executor->purge();

        $loader = $this->getFixtureLoader($container, $this->classes);

        $executor->execute($loader->getFixtures(), true);

        if (isset($name) && isset($backup)) {
            $om = $executor->getObjectManager();
            $this->preReferenceSave($om, $executor, $backup);

            $executor->getReferenceRepository()->save($backup);
            copy($name, $backup);

            $this->postReferenceSave($om, $executor, $backup);
        }

        return $executor;
    }

    /**
     * Callback function to be executed after Schema creation.
     * Use this to execute acl:init or other things necessary.
     */
    protected function postFixtureSetup()
    {

    }

    /**
     * Callback function to be executed after Schema restore
     * @return WebTestCase
     */
    protected function postFixtureRestore()
    {

    }

    /**
     * Callback function to be executed before Schema restore
     *
     * @param ObjectManager $manager The object manager
     * @param ProxyReferenceRepository $referenceRepository The reference repository
     * @return WebTestCase
     */
    protected function preFixtureRestore(ObjectManager $manager, ProxyReferenceRepository $referenceRepository)
    {

    }

    /**
     * Callback function to be executed after save of references
     *
     * @param ObjectManager $manager The object manager
     * @param AbstractExecutor $executor Executor of the data fixtures
     * @param string $backupFilePath Path of file used to backup the references of the data fixtures
     * @return WebTestCase
     */
    protected function postReferenceSave(ObjectManager $manager, AbstractExecutor $executor, $backupFilePath)
    {

    }

    /**
     * Callback function to be executed before save of references
     *
     * @param ObjectManager $manager The object manager
     * @param AbstractExecutor $executor Executor of the data fixtures
     * @param string $backupFilePath Path of file used to backup the references of the data fixtures
     * @return WebTestCase
     */
    protected function preReferenceSave(ObjectManager $manager, AbstractExecutor $executor, $backupFilePath)
    {

    }
}