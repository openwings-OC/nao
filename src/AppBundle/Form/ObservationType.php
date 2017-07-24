<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt', DateType::class, array(
                'label' => 'Date de l\'observation',
                'data' => new \DateTime()
            ))
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class)
            ->add('image', TextType::class)
            ->add('comment', TextareaType::class)
            ->add('specy', EntityType::class, array(
                'label' => 'Choisir l\'espèce observée',
                'class' => 'AppBundle:Taxref',
                'query_builder' => function( EntityRepository $er){
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nomVern', 'ASC')
                        ->where('s.cdTaxsup > :taxsup')
                        ->setParameter('taxsup', 0);
                },
            ))
            ->add('image', ImageType::class)
            ->add('save', SubmitType::class, array(
                'label' => 'Envoyer'
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
