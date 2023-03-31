<?php

namespace App\Form\Appli;

use App\Entity\Appli\Bookroom;
use App\Entity\Appli\Registration;
use App\Repository\Appli\BookroomRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('seance', EntityType::class, [
                'label'=> 'Catégorie',
                'attr' => [
                    'class' => 'form-control form-control-sm',
                ],
                'class' => Bookroom::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.dateBookAt', 'ASC');
                },
                'choice_label' => function ($member) {
                    return $member->getDateBookAt()->format('d m Y');
                },
                'choice_attr' => function (Bookroom $bookroom, $key, $index) {
                    return ['data-data' => $bookroom->getDateBookAt()->format('d m Y') ];
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Registration::class,
        ]);
    }
}
