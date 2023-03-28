<?php

namespace App\Form\Appli;

use App\Entity\Appli\Bookroom;
use App\Entity\Appli\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                'label' => 'Jour',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('hourBookOpenAt', TimeType::class,[
                'label' => 'Ouverture du cours',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('hourBookClosedAt', TimeType::class,[
                'label' => 'Fermeture du cours',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Publié pour les étudiants?',
                'required' => false
            ])
            ->add('forme', ChoiceType::class, [
                'label' => 'Forme',
                'choices'  => [
                    'Distanciel' => "Distanciel",
                    'Présentiel' => 'Presentiel',
                ],
                'choice_attr' => [
                    'Distanciel' => ['data-data' => 'Distanciel'],
                    'Présentiel' => ['data-data' => 'Présentiel'],
                ],
                'required' => false
            ])
            ->add('linkDistanciel', TextType::class, [
                'label' => 'Lien de visio',
                'required' => false
            ])
            ->add('place', IntegerType::class,[
                'label' => 'Nombre de place',
                'required' => false
            ])
            ->add('room', EntityType::class,[
                'class' => Room::class,
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
