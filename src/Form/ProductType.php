<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Taxon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('description')
            ->add('tags')
            ->add('price')
            ->add('discount')
            ->add('priceTtc')
            ->add('stock')
            ->add('taxon', EntityType::class, ['class' => Taxon::class, 'choice_label' => 'label', 'required' => false])
            ->add('image', FileType::class, ['mapped' => false, 'required' => false])
            ->add('isNewness', CheckboxType::class, ['label' => 'Nouveauté', 'required' => false])
            ->add('isPromo', CheckboxType::class, ['label' => 'Promotion', 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
