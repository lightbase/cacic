<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 27/08/15
 * Time: 11:00
 */

namespace Cacic\MultiBundle\DataFixtures\ORM;

use Cacic\MultiBundle\DataFixtures\AbstractDataFixture as AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Cacic\MultiBundle\Entity\Sites;

/**
 * Loads data only on "prod".
 */
class LoadSiteData extends AbstractFixture
{
    /**
     * @override
     * @param ObjectManager $manager
     */
    protected function doLoad(ObjectManager $manager)
    {
        $site = new Sites();
        $site->setDbHost('localhost');
        $site->setDbPassword(null);
        $site->setDbPort(null);
        $site->setDbUrl(null);
        $site->setDbUser('test');
        $site->setSubdir('test');
        $site->setSubdomain(null);
        $site->setUsername('test');

        $this->addReference('sites', $site);
        $manager->persist($site);

        $manager->flush();
    }

    /**
     * @override
     */
    protected function getEnvironments()
    {
        return array('test');
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 101;
    }
}