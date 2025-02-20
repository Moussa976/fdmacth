<?php

namespace App\Form;

use App\Entity\FeuilleMatch;
use App\Entity\Joueur;
use App\Repository\JoueurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeuilleMatchType extends AbstractType
{
    private $joueurRepository;

    public function __construct(JoueurRepository $joueurRepository)
    {
        $this->joueurRepository = $joueurRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $matche = $options['matche']; // Récupérer le match depuis les options

        $joueursEquipe1 = $this->joueurRepository->findBy(['equipe' => $matche->getEquipe1()]);
        $joueursEquipe2 = $this->joueurRepository->findBy(['equipe' => $matche->getEquipe2()]);

        $builder
            ->add('titulairesEquipe1', ChoiceType::class, [
                'choices' => $options['joueursEquipe1'], // Liste des objets Joueur
                'choice_label' => function (Joueur $joueur) {
                    return $joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
                },
                'choice_value' => 'id', // Ce qui sera enregistré (ID)
                'multiple' => true,
                'expanded' => false, // Checkbox
            ])
            ->add('remplacantsEquipe1',  ChoiceType::class, [
                'choices' => $options['joueursEquipe1'], // Liste des objets Joueur
                'choice_label' => function (Joueur $joueur) {
                    return $joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
                },
                'choice_value' => 'id', // Ce qui sera enregistré (ID)
                'multiple' => true,
                'expanded' => true, // Checkbox
            ])
            ->add('dirigeantsEquipe1', TextType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('titulairesEquipe2', ChoiceType::class, [
                'choices' => $options['joueursEquipe2'], // Liste des objets Joueur
                'choice_label' => function (Joueur $joueur) {
                    return $joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
                },
                'choice_value' => 'id', // Ce qui sera enregistré (ID)
                'multiple' => true,
                'expanded' => true, // Checkbox
            ])
            ->add('remplacantsEquipe2',  ChoiceType::class, [
                'choices' => $options['joueursEquipe2'], // Liste des objets Joueur
                'choice_label' => function (Joueur $joueur) {
                    return $joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
                },
                'choice_value' => 'id', // Ce qui sera enregistré (ID)
                'multiple' => true,
                'expanded' => true, // Checkbox
            ])
            ->add('dirigeantsEquipe2', TextType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('arbitres', TextType::class, [
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FeuilleMatch::class,
            'matche' => null,
            'joueursEquipe1' => [],
            'joueursEquipe2' => [],
        ]);
    }

}
