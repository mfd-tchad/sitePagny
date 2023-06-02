<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    /* The form for users admin does contains roles but no password at this time */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Lastname', TextType::class, [
                'required'   => true,
                'help' => 'Between 2 and 30 characters',
                ])
            ->add('Firstname', TextType::class, [
                'required'   => true,
                'help' => 'Between 2 and 25 characters',
                ])
            ->add('username', TextType::class, [
                'required'   => true,
                'help' => 'Entre 3 et 25 caractères',
                ])
            ->add('email', EmailType::class, [
                'required'   => false,
                'label' => 'E-mail Address',
                'help' => 'example@domain.zz',
                ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Role',
                'choices' => $this->getChoices(),
                'multiple' => true])
            ->add('isVerified', CheckboxType::class, [
                'required'   => false,
                'label' => 'Activated',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    // On inverse la clé et la valeur pour un affichage correct de la liste des types d'évènement
    private function getChoices()
    {
        $choices = User::TYPE_ROLE;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
