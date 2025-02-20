<?php

namespace App\Controller;

use App\Entity\FeuilleMatch;
use App\Entity\Matche;
use App\Form\MatcheType;
use App\Repository\JoueurRepository;
use App\Repository\MatcheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/matche")
 */
class MatcheController extends AbstractController
{
    /**
     * @Route("/", name="app_matche_index", methods={"GET"})
     */
    public function index(MatcheRepository $matcheRepository): Response
    {

        return $this->render('matche/index.html.twig', [
            'matches' => $matcheRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_matche_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MatcheRepository $matcheRepository): Response
    {
        $matche = new Matche();
        $feuilleMatch = new FeuilleMatch();

        $form = $this->createForm(MatcheType::class, $matche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matche->setFeuilleMatch($feuilleMatch);
            $matcheRepository->add($matche, true);

            return $this->redirectToRoute('app_matche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matche/new.html.twig', [
            'matche' => $matche,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_matche_show", methods={"GET"})
     */
    public function show(Matche $matche, EntityManagerInterface $em): Response
    {
        if ($matche->getStatut() === 'à venir' && $matche->getLadate() <= new \DateTime()) {
            $matche->setStatut('en cours');
            $em->flush();
        }

        return $this->render('matche/show.html.twig', [
            'matche' => $matche,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_matche_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Matche $matche, MatcheRepository $matcheRepository): Response
    {
        $form = $this->createForm(MatcheType::class, $matche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matcheRepository->add($matche, true);

            return $this->redirectToRoute('app_matche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matche/edit.html.twig', [
            'matche' => $matche,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_matche_delete", methods={"POST"})
     */
    public function delete(Request $request, Matche $matche, MatcheRepository $matcheRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $matche->getId(), $request->request->get('_token'))) {
            $matcheRepository->remove($matche, true);
        }

        return $this->redirectToRoute('app_matche_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/terminer", name="app_matche_terminer")
     */
    public function terminer(Matche $matche, EntityManagerInterface $em): Response
    {
        $matche->setStatut('terminé');
        $em->flush();

        $this->addFlash('success', 'Match marqué comme terminé.');
        return $this->redirectToRoute('app_matche_show', ['id' => $matche->getId()]);
    }

    /**
     * @Route("/{id}/feuille-pdf", name="app_matche_feuille_pdf")
     */
    public function feuillePdf(Matche $matche, JoueurRepository $joueurRepository): Response
    {
        // Récupérer tous les joueurs des deux équipes
        $joueursEquipe1 = $joueurRepository->findBy(['equipe' => $matche->getEquipe1()]);
        $joueursEquipe2 = $joueurRepository->findBy(['equipe' => $matche->getEquipe2()]);

        // Indexer les joueurs par leur ID pour accès facile dans Twig
        $joueursIndexed = [];
        foreach (array_merge($joueursEquipe1, $joueursEquipe2) as $joueur) {
            $joueursIndexed[$joueur->getId()] = $joueur;
        }

        $html = $this->renderView('matche/feuille_match_pdf.html.twig', [
            'matche' => $matche,
            'joueurs' => $joueursIndexed, // On passe le tableau indexé par ID
        ]);

        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true); // Autorise le chargement des images via HTTP
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isPhpEnabled', true);
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="feuille_match.pdf"',
            ]
        );
    }
}
