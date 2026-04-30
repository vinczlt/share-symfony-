<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FichieramiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('userAccepter', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'email', // Ce qui s'affiche pour chaque ami (nom, email, etc.)
            'multiple' => true,        // On peut choisir plusieurs amis
            'expanded' => true,        // Affiche des cases à cocher (false = menu déroulant)
            'label' => 'Partager avec vos amis :'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
