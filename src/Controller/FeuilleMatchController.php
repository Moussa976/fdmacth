<?php

namespace App\Controller;

use App\Entity\FeuilleMatch;
use App\Entity\Matche;
use App\Form\FeuilleMatchType;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeuilleMatchController extends AbstractController
{
    /**
     * @Route("/feuille-match/{id}/remplir", name="feuille_match_remplir")
     */
    public function remplirFeuille(Matche $matche, Request $request, JoueurRepository $joueurRepository, EntityManagerInterface $em): Response
    {
        // Vérifier que le match est "à venir"
        if ($matche->getStatut() !== 'à venir') {
            $this->addFlash('error', 'Vous ne pouvez pas remplir la feuille de match après le début.');
            return $this->redirectToRoute('app_matche_index');
        }

        $feuilleMatch = $matche->getFeuilleMatch() ?? new FeuilleMatch();
        $feuilleMatch->setMatche($matche);


        $joueursEquipe1 = $joueurRepository->findBy(['equipe' => $matche->getEquipe1()]);
        $joueursEquipe2 = $joueurRepository->findBy(['equipe' => $matche->getEquipe2()]);

        $form = $this->createForm(FeuilleMatchType::class, $feuilleMatch, [
            'matche' => $matche,
            'joueursEquipe1' => $joueursEquipe1,
            'joueursEquipe2' => $joueursEquipe2,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupération manuelle des champs non mappés
            $dirigeantsEquipe1 = $form->get('dirigeantsEquipe1')->getData();
            $dirigeantsEquipe2 = $form->get('dirigeantsEquipe2')->getData();
            $arbitres = $form->get('arbitres')->getData();


            // Transformation en tableau (explode si séparés par des virgules)
            $feuilleMatch->setDirigeantsEquipe1($dirigeantsEquipe1 ? array_map('trim', explode(',', $dirigeantsEquipe1)) : []);
            $feuilleMatch->setDirigeantsEquipe2($dirigeantsEquipe2 ? array_map('trim', explode(',', $dirigeantsEquipe2)) : []);
            $feuilleMatch->setArbitres($arbitres ? array_map('trim', explode(',', $arbitres)) : []);



            // On transforme les joueurs en IDs pour les stocker en JSON
            $feuilleMatch->setTitulairesEquipe1(array_map(function ($j) {
                return $j->getId();
            }, $feuilleMatch->getTitulairesEquipe1()));
            $feuilleMatch->setRemplacantsEquipe1(array_map(function ($j) {
                return $j->getId();
            }, $feuilleMatch->getRemplacantsEquipe1()));

            $feuilleMatch->setTitulairesEquipe2(array_map(function ($j) {
                return $j->getId();
            }, $feuilleMatch->getTitulairesEquipe2()));
            $feuilleMatch->setRemplacantsEquipe2(array_map(function ($j) {
                return $j->getId();
            }, $feuilleMatch->getRemplacantsEquipe2()));


            // Sauvegarder
            $em->persist($feuilleMatch);
            $em->flush();

            $this->addFlash('success', 'Feuille de match remplie avec succès.');
            return $this->redirectToRoute('app_matche_index');
        }

        return $this->render('feuille_match/remplir.html.twig', [
            'matche' => $matche,
            'form' => $form->createView(),
        ]);
    }
}
