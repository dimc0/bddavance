<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Products;
use App\Entity\Order;
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
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name', // tu peux aussi mettre 'id'
            ])
            ->add('productsOrders', EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'id',
                'multiple' => true,
                'expanded' => true, // cases à cocher pour les commandes
                'mapped' => false,  // optionnel si tu veux gérer la persistance manuellement
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
