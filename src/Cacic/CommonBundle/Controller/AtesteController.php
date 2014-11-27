<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 10/02/14
 * Time: 15:46
 */

namespace Cacic\CommonBundle\Controller;

use Cacic\CommonBundle\Entity\Ateste;
use Cacic\CommonBundle\Entity\AtesteRedes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Form\Type\LogPesquisaType;
use Cacic\CommonBundle\Form\Type\AtesteType;


class AtesteController extends Controller {

    /**
     * Realiza ateste por local
     *
     * @param Request $request
     */

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');
        $locale = $request->getLocale();

        $form = $this->createForm( new LogPesquisaType() );

        $ateste = $this->createForm (new AtesteType() );

        if ( $request->isMethod('POST') )
        {
            $data = $request->get('log_pesquisa');
            $ateste_data = $request->get('Ateste');
            $listaRedes = $request->get('redes');

            $filtroLocais = array(); // Inicializa array com locais a pesquisar
            foreach ( $data['idLocal'] as $locais ) {
                array_push( $filtroLocais, $locais );
            }


            // Só grava os dados se tiver lista de redes no formulário
            if (!empty($listaRedes)) {

                // Pega usuário da requisição
                $usuario = $request->getUser();

                // Cria objeto do ateste
                $ateste_obj = new Ateste();

                // Pega valores do formulário
                $ateste_obj->setData( new \DateTime());
                $ateste_obj->setUsuario($usuario);
                $ateste_obj->setDescricao($ateste_data['descricao']);
                $ateste_obj->setAtestado($ateste_data['atestado']);
                $ateste_obj->setDetalhes($ateste_data['detalhes']);
                $ateste_obj->setQualidadeServico($ateste_data['qualidade_servico']);

                // Grava dados no banco
                $em->persist($ateste_obj);
                $em->flush();


                // Agora relaciona redes
                foreach ($listaRedes as $rede) {
                    // Pega número de computadores para a rede
                    $estacoes = $request->get("rede_$rede");

                    // Armazena relação entre ateste e redes
                    $ateste_rede = new AtesteRedes();
                    $ateste_rede->setRede($em->getRepository('CacicCommonBundle:Rede')->find($rede));
                    $ateste_rede->setAteste($ateste_obj);
                    $ateste_rede->setEstacoes($estacoes);

                    $em->persist($ateste_rede);
                }

                // COMMIT
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Ateste realizado com sucesso!');
            }

            $logs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:LogAcesso')
                ->pesquisar( $data['dtAcaoInicio'], $data['dtAcaoFim'], $filtroLocais);

            //convertando a string em formato pt-BR para en-EN
            $dtAcaoInicio = $data['dtAcaoInicio'];
            $dtAcaoInicio = substr($dtAcaoInicio,6,4)."-".substr($dtAcaoInicio,3,2)."-".substr($dtAcaoInicio,0,2);
            $dtAcaoFim = $data['dtAcaoInicio'];
            $dtAcaoFim = substr($dtAcaoFim,6,4)."-".substr($dtAcaoFim,3,2)."-".substr($dtAcaoFim,0,2);

        }

        return $this->render( 'CacicCommonBundle:Ateste:index.html.twig',
            array(
                'locale'=> $locale,
                'form' => $form->createView(),
                'ateste' => $ateste->createView(),
                'logs' => ( isset( $logs ) ? $logs : null ),
                'dtAcaoInicio' => $dtAcaoInicio,
                'dtAcaoFim'=> $dtAcaoFim
            )
        );
    }

} 