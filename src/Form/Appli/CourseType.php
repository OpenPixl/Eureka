<?php

namespace App\Form\Appli;

use App\Entity\Admin\Member;
use App\Entity\Appli\Course;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('name', TextType::class,  [
                'required' => false
            ])
            ->add('level')
            ->add('teacher', EntityType::class, [
                'label'=> "Choix de l'enseignant.e",
                'class' => Member::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->andWhere('m.typemember = :typemember')
                        ->setParameter('typemember', 'Enseignant.e')
                        ->orderBy('m.firstName', 'ASC');
                },
                'choice_label' => 'firstName',
                'choice_attr' => function (Member $member, $key, $index) {
                    return ['data-data' => $member->getFirstName()." ". $member->getLastName()];
                }
            ])
            ->add('logo', FileType::class, [
                'label' => 'logo :',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10000k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'iamge/jpg'
                        ],
                        'mimeTypesMessage' => 'Attention, veuillez charger un fichier au format jpg ou png',
                    ])
                ],
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
