<?php

namespace Cacic\CommonBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 *
 * @author lightbase
 *
 */
class Cacic26Controller extends Controller
{
    /**
     *
     * Tela de importação de dados do Gerente 2.6
     */
   public function importardadosAction( Request $request )
    {
        $form = $this->createFormBuilder()
            ->add('arquivo', 'file', array('label' => 'Arquivo', 'attr' => array( 'accept' => '.zip' )))
            ->getForm();

        $form->handleRequest( $request );

        if ( $form->isValid() )
        {
            {
                // grava no diretório src/Cacic/CommonBundle/Resources/data
                $dirMigracao = realpath( dirname(__FILE__) .'/../../../../src/Cacic/CommonBundle/Resources/data/' );

                $fileName = 'importacao'.'.zip';
                $form['arquivo']->getData()->move($dirMigracao, $fileName);


                $this->get('session')->getFlashBag()->add('success', 'Envio realizado com sucesso!');
            }

            return $this->redirect( $this->generateUrl( 'cacic_migracao_cacic26') );
        }

        return $this->render(
            'CacicCommonBundle:Cacic26:migracao.html.twig',
            array( 'form' => $form->createView() )
        );
    }

}