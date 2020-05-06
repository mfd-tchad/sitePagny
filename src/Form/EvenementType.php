<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, ['choices' => $this->getChoices()])
            // ->add('type', ChoiceType::class, ['choices' => Evenement::TYPE_EVENEMENT ])
            ->add('titre')
            ->add('date')
            ->add('description')
            ->add('imageFile', FileType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }

    // On inverse la clé et la valeur pour un affichage correct de la liste des types d'évènement
    private function getChoices()
    {
        $choices=Evenement::TYPE_EVENEMENT;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
