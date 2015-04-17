<?php

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

/**
 *
 * Formulário de PESQUISA por LOGs de acesso ou atividades
 * @author lightbase
 *
 */
class SoPesquisaType extends AbstractType
{

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
		
		$builder->add(
			'idLocal',
			'entity',
			array(
				'class' => 'CacicCommonBundle:So',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('so')
                        ->orderBy('so.teDescSo', 'ASC');
                },
				'property' => 'teDescSo',
                'multiple' => true,
                'required'  => true,
                'expanded'  => true,
                'label'=> 'Selecione o Local:'
			)
		);

        $builder->add(
            'usuario',
            null,
            array( 'label'=>'', 'max_length'=>30, 'required'  => true)
        );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'log_pesquisa';
    }

}