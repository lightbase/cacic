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
class InativoCommand extends ContainerAwareCommand {
    protected function configure()
    {
        $this
            ->setName('cacic:inativo')
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

        $this->addSql("UPDATE computadores SET ativo = 'f'  WHERE dt_hr_inclusao <= (CURRENT_DATE() - ".$dias.")");


        //$output->writeln($text);
    }
} 