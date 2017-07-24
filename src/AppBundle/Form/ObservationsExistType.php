<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObservationsExistType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('specy', EntityType::class, array(
                'label' => 'Choisir l\'espèce observée',
                'class' => 'AppBundle:Taxref',
                'query_builder' => function( EntityRepository $er){
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nomVern', 'ASC')
                        ->where('s.cdTaxsup > :taxsup')
                        ->setParameter('taxsup', 0);
                },
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Observation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_observation';
    }


}
