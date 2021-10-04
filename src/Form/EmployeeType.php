<?php

namespace App\Form;

use App\Entity\Contract;
use App\Entity\Employee;
use App\Entity\Sector;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormTypeInterface;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [

            ])

            // ->add('roles')

            ->add('password', PasswordType::class)

            // ->add('plainPassword', RepeatedType::class, [
            //     'type' => PasswordType::class,
            //     'mapped' => false,
            //     'invalid_message' => 'The password fields must match.',
            //     'options' => ['attr' => ['class' => 'password-field']],
            //     'required' => true,
            //     'first_options'  => ['label' => 'Password'],
            //     'second_options' => ['label' => 'Repeat Password'],
            //     'attr' => ['autocomplete' => 'new-password'],
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Please enter a password',
            //         ]),
            //         new Length([
            //             'min' => 8,
            //             'minMessage' => 'Your password should be at least {{ limit }} characters',

            //             'max' => 4096,
            //         ]),
            //     ],
            // ])

            ->add('employeeName')
            ->add('employeeFirstName')
            ->add('employeePhoto' , FileType::class, [
                'label'=> 'image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Fichier invalide',
                        'maxSizeMessage' => 'Fichier trop lourd'
                    ])
                ]
            ])
            ->add('sector', EntityType::class, [
                'class' => Sector::class,
                'choice_label' => function ($sector) {
                    return $sector->getSectorName();
                },      
                'multiple' => false,
                'expanded' => false,
                ])
                ->add('contract', EntityType::class, [
                    'class' => Contract::class, 
                    'choice_label' => function ($contract) {
                        return $contract->getContractType();
                    },      
                    'multiple' => false,
                    'expanded' => false,
                    ])
            ->add('endContract', TypeDateType::class, [
                'years' => range(date('Y'), date('Y')+20),
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
