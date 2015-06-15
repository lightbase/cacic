<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 01/04/15
 * Time: 12:18
 */

namespace Cacic\CommonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Comando que desativa computadores de um período definido,
 */
class WmiCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this->setName('cacic:wmi')
            ->setDescription('Gera relatório WMI');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inicio = new \DateTime();
        $inicio_formato = $inicio->format('d/m/Y H:m:s');
        $output->writeln("Início da execução em $inicio_formato");


        // Conexão e execução do arquivo
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        $sql = "SELECT gera_relatorio_wmi()";
        $update = $em->getConnection()->prepare($sql);
        $update->execute();

        $fim = new \DateTime();
        $fim_formato = $fim->format('d/m/Y H:m:s');
        $output->writeln("Fim da execução em $fim_formato");

        $interval = $inicio->diff($fim);
        $interval_formato = $interval->format("%R%H:m:s");
        $output->writeln("Tempo de execução: $interval_formato");
    }
} 