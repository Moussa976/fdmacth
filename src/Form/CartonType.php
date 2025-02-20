<?php

namespace App\Form;

use App\Entity\Carton;
use App\Entity\Joueur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('joueur', EntityType::class, [
                'class' => Joueur::class,
                'choices' => $options['joueurs'],
                'choice_label' => function ($joueur) {
                    return $joueur->getEquipe()->getNom().' - '.$joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
                },
            ])
            ->add('minute', IntegerType::class)
            ->add('couleur', ChoiceType::class, [
                'choices' => [
                    'Jaune' => 'jaune',
                    'Rouge' => 'rouge',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carton::class,
            'joueurs' => [], // On attend les joueurs comme option
        ]);
    }
}
