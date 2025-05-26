<?php

namespace App\Controller;

use App\Entity\Cautionnement;
use App\Form\CautionnementType;
use App\Repository\CautionnementRepository;
use App\Repository\AppartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/cautionnement')]
class CautionnementController extends AbstractController
{
    #[Route('/get-personnes/{appartementId}', name: 'get_personnes_by_appartement')]
public function getPersonnesByAppartement(int $appartementId, AppartementRepository $appartementRepo): JsonResponse
{
    $appartement = $appartementRepo->find($appartementId);

    if (!$appartement) {
        return new JsonResponse(['error' => 'Appartement non trouvé'], 404);
    }

    $proprietaire = $appartement->getProprietaire();

    if (!$proprietaire) {
        return new JsonResponse([]);
    }

    $data = [
        [
            'id' => $proprietaire->getId(),
            'text' => $proprietaire->getNom() . ' ' . $proprietaire->getPrenom(),
        ]
    ];

    return new JsonResponse($data);
}

    #[Route('/', name: 'app_cautionnement_index', methods: ['GET'])]
    public function index(Request $request, CautionnementRepository $cautionnementRepository): Response
    {
        $mois = $request->query->get('mois');
        $annee = $request->query->get('annee');

        $queryBuilder = $cautionnementRepository->createQueryBuilder('c');

        if ($mois) {
            $queryBuilder->andWhere('c.Mois = :mois')->setParameter('mois', $mois);
        }

        if ($annee) {
            $dateDebut = new \DateTime("$annee-01-01");
            $dateFin = new \DateTime("$annee-12-31");
            $queryBuilder->andWhere('c.date_paiement BETWEEN :dateDebut AND :dateFin')
                         ->setParameter('dateDebut', $dateDebut)
                         ->setParameter('dateFin', $dateFin);
        }

        $cautionnements = $queryBuilder->getQuery()->getResult();

        // ✅ Nouveau calcul du montant total
        $totalMontant = array_reduce($cautionnements, function ($carry, $c) {
            $montantObj = $c->getMontant(); // par exemple un objet FraisSyndic
            $montant = $montantObj ? $montantObj->getMontant() : 0;
            return $carry + $montant;
        }, 0);

        return $this->render('cautionnement/index.html.twig', [
            'cautionnements' => $cautionnements,
            'totalMontant' => $totalMontant,
            'mois' => $mois,
            'annee' => $annee,
        ]);
    }


   #[Route('/new', name: 'app_cautionnement_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
{
    $cautionnement = new Cautionnement();
    $form = $this->createForm(CautionnementType::class, $cautionnement);
    $form->handleRequest($request);
    $user = $security->getUser();

    if ($form->isSubmitted() && $form->isValid()) {
        $moisSelectionnes = $form->get('Mois')->getData();

        if (empty($moisSelectionnes)) {
            $this->addFlash('error', 'Veuillez sélectionner au moins un mois.');
            return $this->renderForm('cautionnement/new.html.twig', [
                'cautionnement' => $cautionnement,
                'form' => $form,
            ]);
        }

        $appartement = $cautionnement->getAppartement();
        $personne = $cautionnement->getPersonne();
        if ($appartement && $personne && $appartement->getProprietaire() !== $personne) {
            $this->addFlash('error', 'Le propriétaire sélectionné ne correspond pas à l\'appartement.');
            return $this->renderForm('cautionnement/new.html.twig', [
                'cautionnement' => $cautionnement,
                'form' => $form,
            ]);
        }

        foreach ($moisSelectionnes as $mois) {
            $newCautionnement = new Cautionnement();
            $newCautionnement->setMontant($cautionnement->getMontant());
            $newCautionnement->setAnnee($cautionnement->getAnnee());
            $newCautionnement->setMois($mois);
            $newCautionnement->setPersonne($cautionnement->getPersonne());
            $newCautionnement->setAppartement($cautionnement->getAppartement());
            $newCautionnement->setNaturePaiement($cautionnement->getNaturePaiement());
            $newCautionnement->setDatePaiement(new \DateTime());
            $newCautionnement->setUser($user);

            $entityManager->persist($newCautionnement);
          
        }

        $entityManager->flush();

        
        return $this->redirectToRoute('app_cautionnement_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('cautionnement/new.html.twig', [
        'cautionnement' => $cautionnement,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_cautionnement_show', methods: ['GET'])]
    public function show(Cautionnement $cautionnement): Response
    {
        return $this->render('cautionnement/show.html.twig', [
            'cautionnement' => $cautionnement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cautionnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cautionnement $cautionnement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CautionnementType::class, $cautionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cautionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cautionnement/edit.html.twig', [
            'cautionnement' => $cautionnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cautionnement_delete', methods: ['POST'])]
    public function delete(Request $request, Cautionnement $cautionnement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cautionnement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cautionnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cautionnement_index', [], Response::HTTP_SEE_OTHER);
    }
}
