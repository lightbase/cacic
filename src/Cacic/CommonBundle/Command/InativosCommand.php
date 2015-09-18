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
class InativosCommand extends ContainerAwareCommand {
    protected function configure()
    {
        $this
            ->setName('cacic:inativos')
            ->setDescription('Desativa computadores no periodo definido')
            ->addArgument('dias', InputArgument::OPTIONAL, 'Período de inatividade em dias')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dias = $input->getArgument('dias');

        // Conexão e execução do arquivo
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        $sql = "UPDATE computador SET ativo = 'f' AND dt_hr_exclusao = now()  WHERE dt_hr_ult_acesso <= (now() - interval '".$dias." days')";
        $update = $em->getConnection()->prepare($sql);
        $update->execute();

        // Atualiza o relatório de licenças
        $sql = "SELECT update_licencas()";
        $update = $em->getConnection()->prepare($sql);
        $update->execute();


        //$output->writeln($text);
    }
} 