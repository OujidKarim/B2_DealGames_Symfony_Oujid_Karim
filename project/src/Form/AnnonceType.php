<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Enum\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('imageFile', FileType::class, [
                'label' => 'Image (JPEG file)',
                'mapped' => false,
            ])
            ->add('categories', EnumType::class, [
                'class' => Categories::class
            ])
            ->add('prix')
            ->add('ville')
            ->add('codePostal')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
