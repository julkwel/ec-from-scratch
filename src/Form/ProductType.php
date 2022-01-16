<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Taxon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('description')
            ->add('tags', TextType::class)
            ->add('price', IntegerType::class, ['label' => 'Prix HT'])
            ->add('discount', IntegerType::class, ['label' => '% Don'])
            ->add('unit', TextType::class, ['label' => 'Unité de mesure'])
            ->add('stock', IntegerType::class, ['label' => 'Nombre en stock'])
            ->add('taxon', EntityType::class, ['class' => Taxon::class, 'choice_label' => 'label', 'required' => false])
            ->add('image', FileType::class, ['mapped' => false, 'attr' => ['class' => 'form-control form-control-sm'], 'required' => false])
            ->add('isNewness', CheckboxType::class, ['label' => 'En nouveauté', 'required' => false])
            ->add('isPromo', CheckboxType::class, ['label' => 'En promotion', 'required' => false])
            ->add('promoDiscount', TextType::class, ['label' => '% Promotion', 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
