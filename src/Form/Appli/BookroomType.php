<?php

namespace App\Form\Appli;

use App\Entity\Appli\Bookroom;
use App\Entity\Appli\Room;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookroomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateBookAt', DateType::class, [
                'label' => 'Date du Cours',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'required' => false,
                'by_reference' => true,
                'attr' => [
                    'class' => 'flatpickr'
                ]
            ])
            ->add('hourBookOpenAt', TimeType::class, [
                'label' => 'Début du Cours',
                'input'  => 'datetime',
                'widget' => 'single_text',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'required' => false,
                'by_reference' => true,
                'attr' => [
                    'class' => 'flatpickrtime'
                ]
            ])
            ->add('hourBookClosedAt', TimeType::class, [
                'label' => 'Fin du Cours',
                'input'  => 'datetime',
                'widget' => 'single_text',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'required' => false,
                'by_reference' => true,
                'attr' => [
                    'class' => 'flatpickrtime'
                ]
            ])
            ->add('isActive')
            ->add('forme', ChoiceType::class, [
                'label' => 'Bannière sur vignette',
                'choices'  => [
                    'A définir' => "a_définir",
                    'Distanciel' => 'distanciel',
                    'Présentiel' => 'présentiel'
                ],
                'choice_attr' => [
                    'A définir' => ['data-data' => 'A définir'],
                    'Distanciel' => ['data-data' => 'Distanciel'],
                    'Présentiel' => ['data-data' => 'Présentiel'],
                ],
            ])
            ->add('linkDistanciel', TextType::class, [
                'label' => 'Lien pour le distanciel',
                'required'=> false
            ])
            ->add('place', NumberType::class, [
                'label' => 'Nombre de place'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bookroom::class,
        ]);
    }
}
