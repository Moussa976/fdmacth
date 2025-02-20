<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Remplacement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemplacementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('joueur_sortant', EntityType::class, [
            'class' => Joueur::class,
            'choices' => $options['joueurs'],
            'choice_label' => function ($joueur) {
                return $joueur->getEquipe()->getNom().' - '.$joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
            },
        ])
        ->add('joueur_entrant', EntityType::class, [
            'class' => Joueur::class,
            'choices' => $options['joueurs'],
            'choice_label' => function ($joueur) {
                return $joueur->getEquipe()->getNom().' - '.$joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
            },
        ])
        ->add('minute', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Remplacement::class,
            'joueurs' => [], // On attend les joueurs comme option
        ]);
    }
}
