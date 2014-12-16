<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 *
 * @author lightbase
 *
 */
class GrupoUsuarioType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {

        $builder->add( 'teGrupoUsuarios', 'text',
            array(
                'max_length' => 50,
                'label' => 'Nome do Grupo de Usuário:'
            )
        );
        $builder->add( 'nmGrupoUsuarios', 'text',
            array(
                'max_length' => 50,
                'label' => 'Abreviação do Grupo de Usuário:'
            )
        );
        $builder->add( 'teMenuGrupo', 'text',
            array(
                'max_length' => 50,
                'required'=>false,
                'label' => 'Acessos:'
            )
        );
        $builder->add( 'csNivelAdministracao', 'number',
            array(
                'max_length' => 50,
                'required'=>false,
                'label' => 'Nivel de Administração:'
            )
        );
        $builder->add( 'teDescricaoGrupo', 'textarea',
            array(
                'max_length' => 100,
                'required'=>false,
                'label' => 'Descreva o Grupo de Usuário:'
            )
        );
    }
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'grupoUsuario';
    }


}