<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $avatars = $options['avatars']; // Liste des avatars disponibles

        $choices = [];
        foreach ($avatars as $avatar) {
            $choices[$avatar->getName()] = $avatar; // Nom et entité Avatar
        }

        $builder->add('avatarPrincipal', RadioType::class, [
            'label' => 'Choisissez votre avatar',
            'choices' => $choices,
            'expanded' => true, // Affiche les choix sous forme de boutons radio
            'multiple' => false, // Ne permet qu'une seule sélection
            'mapped' => true,
            'required' => true,
            'attr' => [
                'class' => 'avatar-selection' // Ajoute une classe pour les styles personnalisés
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'avatars' => [],  // Liste des avatars
        ]);
    }
}
