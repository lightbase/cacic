<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * Tela inicial do Cacic
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function indexAction(Request $request)
	{
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser()->getIdUsuario();
        $nivel = $em->getRepository('CacicCommonBundle:Usuario' )->nivel($usuario);

		$estatisticas = array(
			'totalCompMonitorados' => $em->getRepository('CacicCommonBundle:Computador')->countAll(),
			'totalInsucessosInstalacao' => $em->getRepository('CacicCommonBundle:InsucessoInstalacao')->count24h(),
			'totalCompPorSO' => $em->getRepository('CacicCommonBundle:Computador')->countPorSO(),
			'totalComp' => $em->getRepository('CacicCommonBundle:LogAcesso')->countPorComputador(),
            'totalComp7Dias' => $em->getRepository('CacicCommonBundle:LogAcesso')->countComputadorDias('0','7'),
            'totalComp14Dias' => $em->getRepository('CacicCommonBundle:LogAcesso')->countComputadorDias('7','14'),
            'semModulos' => $em->getRepository("CacicCommonBundle:Rede")->semModulos(),
            'acoesRede' => $em->getRepository("CacicCommonBundle:Rede")->acoesPorRede()
        );

        // Verifica se há agentes ativos
        $agentes = $this->agenteAtivo($em);

        $user = $this->getUser();
		
		return $this->render(
			'CacicCommonBundle:Default:index.html.twig',
			array(
				'estatisticas' => $estatisticas,
                'nivel' => $nivel[0],
                'agentes' => $agentes,
                'user' => $user
			)
		);
	}

    /*
     * Página de download dos agentes
     */

    public function downloadsAction() {
        return $this->render('CacicCommonBundle:Default:downloads.html.twig');
    }

    public function translationAction() {
        return $this->render('CacicCommonBundle:Default:translation.html.twig');
    }

    public function agenteAtivo($em) {
        $logger = $this->get('logger');

        // Encontra agentes ativos
        $rootDir = $this->container->get('kernel')->getRootDir();
        $webDir = $rootDir . "/../web/";
        $downloadsDir = $webDir . "downloads/";
        if (!is_dir($downloadsDir)) {
            mkdir($downloadsDir);
        }
        $cacicDir = $downloadsDir . "cacic/";
        if (!is_dir($cacicDir)) {
            mkdir($cacicDir);
        }

        // Varre diretório do Cacic
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->directories()->in($cacicDir);

        $tipo_so = $em->getRepository('CacicCommonBundle:TipoSo')->findAll();

        $found = false;
        $platforms = 0;
        foreach($finder as $version) {
            //$logger->debug("1111111111111111111111111111111 ".$version->getFileName());
            if ($version->getFileName() == 'current') {
                $found = true;

                foreach($tipo_so as $so) {
                    $agentes_path = $version->getRealPath() . "/" . $so->getTipo();

                    $agentes = new Finder();
                    $agentes->files()->in($agentes_path);

                    if ($agentes->count() == 0) {
                        $platforms += 1;
                        $this->get('session')
                            ->getFlashBag()
                            ->add(
                                'notice',
                                '<p>Não foram encontrados agentes para a plataforma '.$so->getTipo().'. Por favor, faça upload dos agentes na interface.</p>'
                            );
                    }
                }
            }
        }

        if (!$found) {
            $this->get('session')
                ->getFlashBag()
                ->add(
                    'notice',
                    'Não existe nenhum agente ativo. Por favor, faça upload dos agentes na interface.'
                );

            return sizeof($tipo_so);
        } else {
            return $platforms;
        }

    }
	
}
