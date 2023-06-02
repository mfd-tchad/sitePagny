<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'required'   => true,
                'label' => 'Identifiant',
                'attr' => [
                    'class' => 'form-control',
                ],
                'help' => 'Entrez votre identifiant pour ce compte'
                ])

            ->add('email', EmailType::class, [
                'required'   => true,
                'label' => 'Email',
                'help' => 'Entrez l\'adresse mail associée à votre compte',
                'attr' => [
                    'placeholder' => 'exemple@xyz.fr',
                ]
                ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
