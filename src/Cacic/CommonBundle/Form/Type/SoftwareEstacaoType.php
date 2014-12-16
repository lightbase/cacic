<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Cacic\CommonBundle\Entity\SoftwareRepository;

/**
 *
 *
 * @author lightbase
 *
 */
class SoftwareEstacaoType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
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
                'property' => 'nmSoftware',
                'empty_value' => 'Selecione',
                'max_length'=>100,
                'label'=>'Software' )
        );
        
        $builder->add(
            'idComputador',
            null,
            array(
                'empty_value' => 'Selecione',
                'label'=>'Computador',
            	'query_builder' => function ( \Doctrine\ORM\EntityRepository $repository )
                                   {
										return $repository->createQueryBuilder('c')
															->orderBy('c.nmComputador')
                                       						->addOrderBy('c.teIpComputador');
                                    }
        	)
        );
        
        $builder->add(
            'nrPatrimonio',
            null,
            array( 'label'=>'Patrimônio' )
        );
		
        $builder->add(
            'idAquisicao',
            null,
            array( 'label'=>'Processo de aquisicao' )
        );
		
        $builder->add(
        	'dtAutorizacao',
	        'date',
	        array(
	        	'widget' => 'single_text',
            	'format' => 'dd/MM/yyyy',
            	'label'=>'Data de autorizacao',
	        	'required' => false,
	        	'attr' => array('class'=>'datepicker_on')
	        )
        );
        
        $builder->add('dtExpiracaoInstalacao',
            'date',
            array(
            	'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label'=>'Data de expiracao',
            	'required' => false,
            	'attr' => array('class'=>'datepicker_on')
            )
        );
        
        $builder->add('dtDesinstalacao',
            'date',
            array(
            	'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label'=>'Data de desinstalacao',
            	'required' => false,
            	'attr' => array('class'=>'datepicker_on')
            )
        );
        
        $builder->add(
            'nrPatrDestino',
            null,
            array( 'label'=>'Patrimonio de destino' )
        );
        
        $builder->add(
            'teObservacao',
            null,
            array( 'label'=>'Observação' )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'SoftwareEstacao';
    }

}