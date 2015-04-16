<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 04/02/14
 * Time: 10:54
 */

namespace Cacic\RelatorioBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function relatorioMenu(FactoryInterface $factory, array $options)
    {
        $logger = $this->container->get('logger');

        // Cria menu
        $menu = $factory->createItem('root');
        //$menu->addChild('Configurações', array('route' => 'cacic_relatorio_hardware_configuracoes'));

        // Carrega lista de classes WMI para coleta
        $em = $this->container->get('doctrine')->getManager();
        $classes = $em->getRepository('CacicCommonBundle:ComputadorColeta')->menu();

        // Adiciona cada uma das classes como slug para um controller
        foreach ($classes as $wmiClass) {
            $menu->addChild($wmiClass['nmClassName'], array(
                'route' => 'cacic_relatorio_hardware_wmi',
                'routeParameters' => array(
                    'classe' => $wmiClass['nmClassName']
                )
            ));
        }

        return $menu;
    }
}