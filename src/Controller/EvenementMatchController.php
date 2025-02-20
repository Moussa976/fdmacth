<?php

namespace App\Controller;

use App\Entity\But;
use App\Entity\Carton;
use App\Entity\Joueur;
use App\Entity\Matche;
use App\Entity\Remplacement;
use App\Form\ButType;
use App\Form\CartonType;
use App\Form\RemplacementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementMatchController extends AbstractController
{
    /**
     * @Route("/match/{id}/but", name="ajouter_but")
     */
    public function ajouterBut(Matche $matche, Request $request, EntityManagerInterface $em): Response
    {
        $feuilleMatch = $matche->getFeuilleMatch();


        if (!$feuilleMatch) {
            $this->addFlash('error', 'Aucune feuille de match associée à ce match.');
            return $this->redirectToRoute('app_matche_index');
        }

        // Récupérer les IDs des joueurs titulaires + remplaçants des deux équipes
        $joueursIds = array_merge(
            $feuilleMatch->getTitulairesEquipe1(),
            $feuilleMatch->getRemplacantsEquipe1(),
            $feuilleMatch->getTitulairesEquipe2(),
            $feuilleMatch->getRemplacantsEquipe2()
        );

        $joueurs = $em->getRepository(Joueur::class)->findBy(['id' => $joueursIds]);

        $but = new But();
        $but->setMatche($matche);

        $form = $this->createForm(ButType::class, $but, [
            'joueurs' => $joueurs, // On passe les joueurs au formulaire
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($but);
            $em->flush();

            $this->addFlash('success', 'But ajouté avec succès.');
            return $this->redirectToRoute('app_matche_index');
        }

        return $this->render('evenement_match/but.html.twig', [
            'matche' => $matche,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/match/{id}/carton", name="ajouter_carton")
     */
    public function ajouterCarton(Matche $matche, Request $request, EntityManagerInterface $em): Response
    {
        $feuilleMatch = $matche->getFeuilleMatch();

        if (!$feuilleMatch) {
            $this->addFlash('error', 'Aucune feuille de match associée à ce match.');
            return $this->redirectToRoute('app_matche_index');
        }

        $joueursIds = array_merge(
            $feuilleMatch->getTitulairesEquipe1(),
            $feuilleMatch->getRemplacantsEquipe1(),
            $feuilleMatch->getTitulairesEquipe2(),
            $feuilleMatch->getRemplacantsEquipe2()
        );
        $joueurs = $em->getRepository(Joueur::class)->findBy(['id' => $joueursIds]);



        $carton = new Carton();
        $carton->setMatche($matche);

        $form = $this->createForm(CartonType::class, $carton, ['joueurs' => $joueurs]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($carton);
            $em->flush();
            return $this->redirectToRoute('app_matche_index');
        }

        return $this->render('evenement_match/carton.html.twig', [
            'matche' => $matche,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/match/{id}/remplacant", name="ajouter_remplacement")
     */
    public function ajouterRemplacement(Matche $matche, Request $request, EntityManagerInterface $em): Response
    {
        $feuilleMatch = $matche->getFeuilleMatch();


        if (!$feuilleMatch) {
            $this->addFlash('error', 'Aucune feuille de match associée à ce match.');
            return $this->redirectToRoute('app_matche_index');
        }

        $joueursIds = array_merge(
            $feuilleMatch->getTitulairesEquipe1(),
            $feuilleMatch->getRemplacantsEquipe1(),
            $feuilleMatch->getTitulairesEquipe2(),
            $feuilleMatch->getRemplacantsEquipe2()
        );
        $joueurs = $em->getRepository(Joueur::class)->findBy(['id' => $joueursIds]);



        $remplacement = new Remplacement();
        $remplacement->setMatche($matche);

        $form = $this->createForm(RemplacementType::class, $remplacement, ['joueurs' => $joueurs]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($remplacement);
            $em->flush();
            return $this->redirectToRoute('app_matche_index');
        }

        return $this->render('evenement_match/remplacement.html.twig', [
            'matche' => $matche,
            'form' => $form->createView(),
        ]);
    }
}
