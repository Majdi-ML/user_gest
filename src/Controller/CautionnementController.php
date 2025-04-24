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
    #[Route('/get-appartements/{personneId}', name: 'get_appartements_by_personne')]
public function getAppartementsByPersonne(int $personneId, AppartementRepository $appartementRepo): JsonResponse
{
    $appartements = $appartementRepo->findBy(['proprietaire' => $personneId]);

    $data = [];

    foreach ($appartements as $appartement) {
        $data[] = [
            'id' => $appartement->getId(),
            'text' => (string) $appartement, // grâce au __toString()
        ];
    }

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

    $totalMontant = array_reduce($cautionnements, fn($carry, $c) => $carry + $c->getMontant(), 0);

    return $this->render('cautionnement/index.html.twig', [
        'cautionnements' => $cautionnements,
        'totalMontant' => $totalMontant,
        'mois' => $mois,
        'annee' => $annee,
    ]);
}

    #[Route('/new', name: 'app_cautionnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , Security $security): Response
    {
        $cautionnement = new Cautionnement();
        $form = $this->createForm(CautionnementType::class, $cautionnement);
        $form->handleRequest($request);
        $user = $security->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $cautionnement->setDatePaiement(new \DateTime());
            $cautionnement->setUser($user);
            $entityManager->persist($cautionnement);
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
