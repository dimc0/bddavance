<?php

namespace App\Form;

use App\Entity\category;
use App\Entity\Products;
use App\Entity\Productsorders;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('stock')
            ->add('description')
            ->add('category_id', EntityType::class, [
                'class' => category::class,
                'choice_label' => 'id',
            ])
            ->add('productsorders', EntityType::class, [
                'class' => Productsorders::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
