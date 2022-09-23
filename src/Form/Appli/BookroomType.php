<?php

namespace App\Form\Appli;

use App\Entity\Appli\Bookroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookroomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateBookAt')
            ->add('hourBookOpenAt')
            ->add('hourBookClosedAt')
            ->add('isActive')
            ->add('forme')
            ->add('linkDistanciel')
            ->add('place')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('room')
            ->add('Course')
            ->add('registrations')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bookroom::class,
        ]);
    }
}
