<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/01/14
 * Time: 12:18
 */

namespace Cacic\CommonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cacic\WSBundle\Helper\OldCacicHelper;


/*
 * Comando que carrega os dados de demonstração para o Cacic. Diferente dos data fixtures,
 * inclui dados de coleta
 */
class DemoCommand extends ContainerAwareCommand {
    protected function configure()
    {
        $this
            ->setName('cacic:demo')
            ->setDescription('Carrega dados de demonstração do Cacic')
            ->addArgument('name', InputArgument::OPTIONAL, 'Nome de quem está executando o comando, só para não perder o atributo')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Prossegue com a carga mesmo se acontecer algum erro')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $text = "Olá {$name}";

        // Conexão e execução do arquivo
        $cacicHelper = new OldCacicHelper($this->getContainer()->get('kernel'));
        $rootDir = $cacicHelper->getRootDir();
        $demo = $rootDir."/src/Cacic/CommonBundle/Resources/data/demo.sql";
        $container = $this->getContainer();


        // Carrega configurações do banco de dados
        $dbhost = $container->getParameter('database_host');
        $db = $container->getParameter('database_name');
        $port = $container->getParameter('database_port');
        $user = $container->getParameter('database_user');
        $pass = $container->getParameter('database_password');

        // A única forma que encontrei foi executar manualmente o psql
        $exec_string = "psql -f $demo -U $user -d $db ";

        if (!empty($dbhost)) {
            $exec_string .= "-h $dbhost ";
        }

        if (!empty($port)) {
            $exec_string .= "-p $port ";
        }

        if (!empty($pass)) {
            // Tem que passar a senha como variável
            $exec("PG_PASSWORD=$pass");
        }

        // Manda executar o psql
        exec($exec_string);

        $force =  ($input->getOption('force'));

        $output->writeln($text);
    }
} 