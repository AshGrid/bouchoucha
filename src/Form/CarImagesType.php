<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CarImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('carImageFiles', CollectionType::class, [
                'entry_type' => VichImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Car Images',
                'entry_options' => [
                    'required' => false,
                    'allow_delete' => true,
                    'download_label' => 'View image',
                    'download_uri' => true,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
