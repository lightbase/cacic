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
class AquisicaoType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add(
            'nrProcesso',
            null,
            array( 'label'=>'Processo de aquisicao:', 'max_length'=>11 )
        );

        $builder->add(
            'nmEmpresa',
            null,
            array( 'label'=>'Nome da empresa:', 'max_length'=>45 )
        );

        $builder->add(
            'nmProprietario',
            null,
            array( 'label'=>'Nome do proprietario:', 'max_length'=>45 )
        );

        $builder->add(
            'nrNotafiscal',
            null,
            array( 'label'=>'Nota Fiscal:', 'max_length'=>20 )
        );
        $builder->add('dtAquisicao',
            'date',
            array( 'widget' => 'single_text',
                'format' => 'dd/MM/yyyy','label'=>'Data de aquisicao',)
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'Aquisicao';
    }

}