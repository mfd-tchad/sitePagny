<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Lastname', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom devrait contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 30,
                    ]),
                ]
            ])
            ->add('Firstname', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom devrait contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 30,
                    ]),
                ]
            ])
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un identifiant',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre mot de passe devrait contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 30,
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'example@domain.zz',
                ],
                'label' => 'E-mail Address',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuiller entrer une adresse mail valide',
                    ]),
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password'
                ],
                'label' => 'Password',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe devrait contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                // Pour le consentement à ce que les données soient enregistrées
                'mapped' => false,
                'label' => 'I accept these personnal data being saved for me to log in',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez cocher la case de consentement',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
