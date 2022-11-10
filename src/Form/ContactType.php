<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required'   => true,
                'help' => 'Entrez votre Nom de Famille',
                ])
            ->add('prenom', TextType::class, [
                'required'   => true,
                'help' => 'Entrez votre Prénom',
                ])
            ->add('tel', TelType::class, [
                'required'   => false,
                'help' => 'Entrez votre Numéro de téléphone',
                ])
            ->add('email', EmailType::class, [
                'required'   => true,
                'help' => 'Entrez votre adresse mail',
                ])
            ->add('sujet', TextType::class, [
                'required'   => true,
                'help' => 'Entrez le sujet de votre message',
                ])
            ->add('message', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
