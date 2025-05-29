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
      // Fetch all cautionnements
        $cautionnements = $cautionnementRepository->findAll();

        // Calculate total montant
        $totalMontant = 0;
        foreach ($cautionnements as $c) {
            if ($c->getMontant()) {
                $totalMontant += $c->getMontant()->getMontant();
            }
        }

        // Organize data by year and appartement
        $dataByYear = [];
        foreach ($cautionnements as $c) {
            $year = $c->getAnnee();
            $month = $c->getMois();
            $monthNum = array_search($month, [
                1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ]);

            if (!$year || !$month || !$monthNum) {
                continue; // Skip if year or month is missing
            }

            // Unique key for grouping: appartement
            $appartementObj = $c->getAppartement();
$appartementKey = $appartementObj ? $appartementObj->getId() : '—';

$personne = $c->getPersonne();
$nom = $personne ? $personne->getNom() . ' ' . $personne->getPrenom() : '—';
$proprietaire = $personne ? $personne->getNom() : '—';

if (!isset($dataByYear[$year])) {
    $dataByYear[$year] = [];
}

if (!isset($dataByYear[$year][$appartementKey])) {
    $dataByYear[$year][$appartementKey] = [
        'nom' => $nom,
        'appartement' => $appartementObj,
        'proprietaire' => $proprietaire,
        'months' => array_fill(1, 12, 'Non'),
        'details' => [],
    ];
}

$dataByYear[$year][$appartementKey]['months'][$monthNum] = 'Oui';

$dataByYear[$year][$appartementKey]['details'][] = [
    'id' => $c->getId(),
    'montant' => $c->getMontant() ? $c->getMontant()->getMontant() : null,
    'mois' => $month,
    'annee' => $year,
    'naturePaiement' => $c->getNaturePaiement() ? $c->getNaturePaiement()->getNature() : null,
    'personne' => $nom,
    'appartement' => $appartementObj,
    'utilisateur' => $c->getUser() ? $c->getUser()->getEmail() : null,
];
        }

        return $this->render('cautionnement/index.html.twig', [
            'cautionnements' => $cautionnements,
            'dataByYear' => $dataByYear,
            'totalMontant' => $totalMontant,
        ]);
    }
    


   #[Route('/new', name: 'app_cautionnement_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
{$cautionnement = new Cautionnement();
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

        $appartements = $form->get('appartement')->getData();
        if (empty($appartements)) {
            $this->addFlash('error', 'Veuillez sélectionner au moins un appartement.');
            return $this->renderForm('cautionnement/new.html.twig', [
                'cautionnement' => $cautionnement,
                'form' => $form,
            ]);
        }

        foreach ($appartements as $appartement) {
            $proprietaire = $appartement->getProprietaire();
            if (!$proprietaire) {
                $this->addFlash('error', 'L\'appartement sélectionné n\'a pas de propriétaire.');
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
                $newCautionnement->setPersonne($proprietaire); // Set proprietor based on apartment
                $newCautionnement->setAppartement($appartement);
                $newCautionnement->setNaturePaiement($cautionnement->getNaturePaiement());
                $newCautionnement->setDatePaiement(new \DateTime());
                $newCautionnement->setUser($user);

                $entityManager->persist($newCautionnement);
            }
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
