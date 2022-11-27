<?php

namespace App\Form\Admin;

use App\Entity\Admin\Member;
use phpDocumentor\Reflection\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Entrez votre Email'
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('complement', TextType::class, [
                'label' => '',
                'required' => false
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Code Postal',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'Commune',
                'required' => false
            ])
            ->add('Mobile', TextType::class, [
                'label' => 'Mobile'
            ])
            ->add('home', TextType::class, [
                'label' => 'Domicile',
                'required' => false
            ])
            ->add('avatarFile', FileType::class, [
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
            //->add('typemember', ChoiceType::class, [
            //    'label' => 'Type',
            //    'choices'  => [
            //        'A définir' => "a-definir",
            //        'Administrateur' => 'Administrateur',
            //        'Enseignant.e' => 'Enseignant.e',
            //        'Etudiant.e' => 'Etudiant.e'
            //    ],
            //    'choice_attr' => [
            //        'A définir' => ['data-data' => 'A définir'],
            //        'Administrateur' => ['data-data' => 'Administrateur'],
            //        'Enseignant.e' => ['data-data' => 'Enseignant.e'],
            //        'Etudiant.e' => ['data-data' => 'Etudiant.e']
            //    ],
            //])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
