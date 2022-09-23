<?php

namespace App\Form\Admin;

use App\Entity\Admin\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('password')
            ->add('firstName', TextareaType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastName', TextareaType::class, [
                'label' => 'Nom'
            ])
            ->add('adress', TextareaType::class, [
                'label' => 'Adresse'
            ])
            ->add('complement', TextareaType::class, [
                'label' => ''
            ])
            ->add('zipcode', TextareaType::class, [
                'label' => 'Code Postal'
            ])
            ->add('city', TextareaType::class, [
                'label' => 'Commune'
            ])
            ->add('Mobile', TextareaType::class, [
                'label' => 'Mobile'
            ])
            ->add('home', TextareaType::class, [
                'label' => 'Domicile'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
