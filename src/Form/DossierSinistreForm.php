<?php

namespace App\Form;

use App\Entity\DossierSinistre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class DossierSinistreForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroPolice')
            ->add('description')
            ->add('etat')
            ->add('constatImageFile', VichImageType::class, [
                'label' => 'Constat Image',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => false,
                'attr' => ['class' => 'form-control-file']
            ])
            ->add('carImages', FileType::class, [
                'label' => 'Car Images',
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control-file',
                    'accept' => 'image/*',
                    'multiple' => 'multiple'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DossierSinistre::class,
        ]);
    }


}
