<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Log;
use Cacic\CommonBundle\Form\Type\LogType;

/**
 *
 * CRUD da Entidade Log
 * @author lightbase
 *
 */
class LogController extends Controller
{

    public function acessoAction(Request $request)
    {
        $log = new Log();
        $form = $this->createForm( new LogType(), $log );
        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
             $data = $form->getData();
             $this->getDoctrine()->getRepository('CacicCommonBundle:Log')->pesquisar($data);
               var_dump($data);die;
            }
        }

        return $this->render( 'CacicCommonBundle:Log:acesso.html.twig', array( 'form' => $form->createView() ) );
    }

    public function atividadeAction()
    {

    }

    public function insucessoinstalacaoAction()
    {

    }

    public function suporteremotoAction()
    {

    }
}
