<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class  UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

              $builder->add('idLocal', 'entity',
                            array(
                            'empty_value' => 'Selecione Local',
                            'class' => 'CacicCommonBundle:Locais',
                            'property' => 'nmlocal',
                            'label'=> 'Local:',
              ));

              $builder->add('idServidorAutenticacao', 'hidden',
                            array( "data"=> '1'
               ));
              $builder->add('TeLocaisSecundarios', 'entity',
                            array(
                            'class' => 'CacicCommonBundle:Usuarios',
                            'property' => 'TeLocaisSecundarios',
                            'multiple' => true,
                            'label'=> 'Local Secundarios:',
               ));

              $builder->add('nmUsuarioAcesso', 'text',
                             array(
                            'label' => 'Login:',
               ));

              $builder ->add('nmUsuarioCompleto', 'text',
                              array(
                             'label'=> 'Nome Completo:',
               ));

              $builder->add('teEmailsContato', 'email',
                            array(
                            'label' => 'Email:',
               ));

              $builder->add('teTelefonesContato', 'number',
                            array(
                            'required' => false,
                            'label' => 'Telefone para Contato:',
              ));

              $builder->add('teSenha', 'password',
                            array(
                            'required' => false,
                            'label' => 'Senha:'
              ));

              $builder->add('Grupo', 'entity',
                            array(
                          'empty_value' => 'Selecione Acesso',
                          'class' => 'CacicCommonBundle:GrupoUsuarios',
                          'property' => 'teGrupoUsuarios',
                          'label'=> 'Selecione Tipo de Acesso:'
              ));

              $builder->add('idGrupoUsuarios', 'textarea',
                            array(
                             'required' => false,
                             'label' => 'Descrição do tipo de acesso:',
                             'max_length' => '255',
              ));
    }
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
     {
        return 'usuario';
     }
 }
