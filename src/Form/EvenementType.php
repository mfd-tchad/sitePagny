<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Event Form builder
 * 
 * @category Class
 * @author Marie-Françoise Dewulf <marie-francoise@mfdewulf.fr>
 */
class EvenementType extends AbstractType
{
    /**
     * Builds form from event class
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, ['choices' => $this->getChoices()])
            ->add('titre')
            ->add('date', DateType::class, [
                'widget' => 'single_text', // for a datePicker
            ])
            ->add('description', CKEditorType::class)
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'asset_helper' => true,
                'allow_delete' => true,
                'label' => 'Parcourir',
                'allow_file_upload' => true,
                'download_uri' => true,
                'image_uri' => static function (Evenement $evenement) {
                    return $evenement->getImage();
                },
                //'upload_max_size_message' => 'Le fichier image est trop volumineux. Taille max autorisée : 2 MB'
            ])
            ->add('pdfFile', VichFileType::class, [
                'required' => false,
                'asset_helper' => true,
                'allow_delete' => true,
                'label' => 'Charger Pdf',
                'allow_file_upload' => true,
                'download_uri' => true,
                ])
        ;
    }

    /**
     * Binds form to Evenement class
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }

    /**
     * On inverse la clé et la valeur pour un affichage correct de la liste des types d'évènement
     */
    private function getChoices()
    {
        $choices = Evenement::TYPE_EVENEMENT;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
