<?php

namespace Cacic\CommonBundle\Form\Type;

use Cacic\CommonBundle\Entity\SoftwareRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 *
 * @author lightbase
 *
 */
class AquisicaoItemType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add(
            'idAquisicao',
            'entity',
            array(
                'class' => 'CacicCommonBundle:Aquisicao',
                'property' => 'nrProcesso',
                'empty_value' => 'Selecione',
                'label'=>'Processo de aquisicao:', 'max_length'=>100 )
        );

        $builder->add(
            'idSoftware',
            'entity',
            array(
                'class' => 'CacicCommonBundle:Software',
                'query_builder' => function(SoftwareRepository $er) {
                        return $er->createQueryBuilder('sw')
                            ->select('sw')
                            ->innerJoin('CacicCommonBundle:PropriedadeSoftware', 'prop', 'WITH', 'sw.idSoftware = prop.software')
                            ->innerJoin('CacicCommonBundle:ClassProperty', 'class','WITH', 'prop.classProperty = class.idClassProperty')
                            ->groupBy('class.idClassProperty, class.nmPropertyName, sw')
                            ->orderBy('sw.nmSoftware');
                    },
                'property'=>'nmSoftware',
                'empty_value' => 'Selecione',
                'label'=>'Software:',
                'max_length'=>100
            )
        );

        $builder->add(
            'idTipoLicenca',
            'entity',
            array(
                'class' => 'CacicCommonBundle:TipoLicenca',
                'property' => 'teTipoLicenca',
                'empty_value' => 'Selecione',
                'label'=>'Tipo Licença:', 'max_length'=>100 )
        );

        $builder->add(
            'qtLicenca',
            null,
            array( 'label'=>'Quantidade de Licencas:', 'max_length'=>100 )
        );
        $builder->add('dtVencimentoLicenca',
            'date',
            array(
            	'widget' => 'single_text',
                'format' => 'dd/MM/yyyy','label'=>'Data de Vencimento',
            	'attr' => array('class'=>'datepicker_on' )
            )
        );
        $builder->add('teObs',
             'textarea',
            array(
                'label'=>'Observação',
                'required'=>false,
                'max_length'=>200
            )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'AquisicaoItem';
    }

}