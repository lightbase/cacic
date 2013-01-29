<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class  UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

              $builder
                ->add('idLocal', 'entity', array(
                    'empty_value' => 'Selecione Local',
                    'class' => 'CacicCommonBundle:Locais',
                    'property' => 'nmlocal',
                    'label'=> 'Local:',
                 ))
                ->add('TeLocaisSecundarios', 'entity', array(
                    'class' => 'CacicCommonBundle:Usuarios',
                    'property' => 'TeLocaisSecundarios',
                    'multiple' => true,
                    'label'=> 'Local Secundarios:',
                 ))
                ->add('NmUsuarioAcesso', 'text', array(
                    'label' => 'Login:',
                 ))
                ->add('nmUsuarioCompleto', 'text', array(
                    'label'=> 'Nome Completo:',
                 ))
                ->add('teEmailsContato', 'email', array(
                    'label' => 'Email:',
                 ))
                ->add('teTelefonesContato', 'number', array(
                  'required' => false,
                    'label' => 'Telefone para Contato:',
                 ))

                ->add('Grupo', 'entity', array(
                  'empty_value' => 'Selecione Acesso',
                  'class' => 'CacicCommonBundle:GrupoUsuarios',
                  'property' => 'teGrupoUsuarios',
                  'label'=> 'Selecione Tipo de Acesso:'
                 ))
                  ->add('idGrupoUsuarios', 'textarea', array(
                  'required' => false,
                  'label' => 'Descrição do tipo de acesso:',
                  'max_length' => '255',
              ))

                  ->getForm();


    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
      {
        $resolver->setDefaults(array(
            'data_class' => 'Cacic\CommonBundle\Entity\Usuarios',
        ));
      }
    public function getName()
     {
        return 'usuario';
     }
 }
