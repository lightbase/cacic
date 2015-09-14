<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 21/07/15
 * Time: 12:52
 */

namespace Cacic\RelatorioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class SoftwareRelatorioType extends AbstractType
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }


    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add(
            'nomeRelatorio',
            'text',
            array(
                'label' => 'Nome do Relatório'
            )
        );

        // Verifica opçoes validas de acordo com o perfil do usuario
        if ($this->authorizationChecker->isGranted('ROLE_GESTAO')) {
            $choices = array(
                'publico' => "Publico",
                'restrito' => "Restrito",
                'pessoal' => "Pessoal"
            );

        } else {
            $choices = array(
                'pessoal' => "Pessoal"
            );
        }

        $builder->add(
            'nivelAcesso',
            'choice',
            array(
                'choices' => $choices,
                'required' => true,
                'label' => 'Nível de acesso',
                'expanded' => false,
                'multiple' => false
            )
        );

        if ($this->authorizationChecker->isGranted('ROLE_GESTAO')) {
            $builder->add(
                'tipo',
                'choice',
                array(
                    'choices' => array(
                        'relatorio' => "Relatório de Software",
                        'excluir' => 'Lista de exclusão'
                    ),
                    'required' => true,
                    'label' => 'Tipo de relatório',
                    'expanded' => false,
                    'multiple' => false
                )
            );
        }

    }

    public function getName() {
        return 'relatorio_software';
    }

}