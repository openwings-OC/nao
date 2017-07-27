<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Observation;


class ObservationEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, array(
                'label' => 'Commentaire'
            ))
            ->add('observationComment', TextareaType::class, array(
                'label' => 'Commentaire du naturaliste'
                ))
            ->add('specy', EntityType::class, array(
                'label' => 'Choisir l\'espèce observée',
                'class' => 'AppBundle:Taxref',
                'attr'    => array('id' => 'observationAdd'),
                'query_builder' => function( EntityRepository $er){
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nomVern', 'ASC')
                        ->where('s.cdTaxsup > :taxsup')
                        ->setParameter('taxsup', 0);
                },
            ))
            ->add('state', ChoiceType::class, array(
                'label' => 'Status',
                'choices' => array(
                    'Non validé' => Observation::STATUS_PENDING,
                    'À revoir' => Observation::STATUS_REVIEW,
                    'Validé' => Observation::STATUS_VALIDATE,
                ),
                'expanded' => true,
                'multiple' => false
            ))
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
