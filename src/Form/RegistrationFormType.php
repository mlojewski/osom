<?php

namespace App\Form;

use App\DTO\RegistrationFormDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'organizationEmail',
                TextType::class,
                [
                    'label' => 'Email organizacji '
                ]
            )
            ->add(
                'organizationName',
                TextType::class,
                [
                    'label' => 'Nazwa organizacji '
                ]
            )
            ->add(
                'userEmail',
                TextType::class,
                [
                    'label' => 'Email administratora '
                ]
            )
            ->add(
                'userName',
                TextType::class,
                [
                    'label' => 'Imię administratora '
                ]
            )
            ->add(
                'userLastName',
                TextType::class,
                [
                    'label' => 'Nazwisko administratora '
                ]
            )
            
            ->add('agreeTerms', CheckboxType::class, [
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Hasło administratora ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegistrationFormDTO::class,
        ]);
    }
}
