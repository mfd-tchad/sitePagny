<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, ['choices' => $this->getChoices()])
            ->add('titre')
            ->add('date',DateType::class)
            ->add('description', CKEditorType::class)
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'asset_helper' => true,
                'allow_delete' => true,
                'label' => 'Parcourir',
                'allow_file_upload' => true,
                'download_uri' => true,
                'image_uri' => static function (Evenement $evenement) {
                    return $evenement->getImage();},
                //'upload_max_size_message' => 'Le fichier image est trop volumineux. Taille max autorisée : 2 MB'
                ])
        ;
    }
    
    /*
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, ['choices' => $this->getChoices()])
            ->add('titre')
            ->add('date',DateType::class)
            ->add('description', CKEditorType::class)
            ->add('imageFile', FileType::class, [
                'required' => false,
                'multiple' => false,
                'mapped' => false,
                'label' => 'Parcourir',
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        //'mimeTypes' => [
                        //    'image/jpg',
                        //    'image/jpeg',
                        //],
                        'mimeTypesMessage' => 'Choisissez une image de taille < 2 Mo SVP',
                        ])
                    ],
                ])
        ;
    }
    */   

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
