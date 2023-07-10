<?php

namespace App\Form\Appli;

use App\Entity\Admin\Member;
use App\Entity\Appli\Course;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('level', TextType::class, [
                'label' => 'Niveau'
            ])
            ->add('logoFile',FileType::class,[
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10000k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Attention, veuillez charger un fichier au format jpg ou png',
                    ])
                ],
            ])
            ->add('teacher', EntityType::class, [
                'label'=> "Choix de l'enseignant",
                'class' => Member::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->andWhere('m.typemember = :member')
                        ->setParameter('member', 'Enseignant')
                        ->orderBy('m.lastName', 'ASC');
                },
                'choice_label' => function ($member) {
                    return $member->getLastName().' '.$member->getFirstName();
                },
                'choice_attr' => function (Member $member, $key, $index) {
                    return ['data-data' => $member->getFirstName().' '.$member->getLastName() ];
                },
                'attr' => [
                    'class' => 'form-select form-select-sm'
                ]
            ])
            ->add('color', ColorType::class, [
                'label' => 'Couleur',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control form-control-color'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
