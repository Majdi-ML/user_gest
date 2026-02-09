<?php

namespace App\Controller;

use App\Entity\TypePapier;
use App\Entity\TypeRassmblement;
use App\Entity\StatusBureau;
use App\Entity\FonctionBureau;
use App\Entity\FraisSyndic;
use App\Entity\FonctionEmploye;
use App\Repository\TypePapierRepository;
use App\Repository\TypeRassmblementRepository;
use App\Repository\StatusBureauRepository;
use App\Repository\FonctionBureauRepository;
use App\Repository\FraisSyndicRepository;
use App\Repository\FonctionEmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/configuration')]
class ConfigurationController extends AbstractController
{
    #[Route('/', name: 'app_configuration_index', methods: ['GET'])]
    public function index(
        TypePapierRepository $typePapierRepo,
        TypeRassmblementRepository $typeRassmblementRepo,
        StatusBureauRepository $statusBureauRepo,
        FonctionBureauRepository $fonctionBureauRepo,
        FraisSyndicRepository $fraisSyndicRepo,
        FonctionEmployeRepository $fonctionEmployeRepo
    ): Response {
        return $this->render('configuration/index.html.twig', [
            'type_papiers' => $typePapierRepo->findAll(),
            'type_rassemblements' => $typeRassmblementRepo->findAll(),
            'status_bureaux' => $statusBureauRepo->findAll(),
            'fonction_bureaux' => $fonctionBureauRepo->findAll(),
            'frais_syndics' => $fraisSyndicRepo->findAll(),
            'fonction_employes' => $fonctionEmployeRepo->findAll(),
        ]);
    }

    // Type Papier
    #[Route('/type-papier/add', name: 'app_configuration_type_papier_add', methods: ['POST'])]
    public function addTypePapier(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $libelle = $request->request->get('libelle');
        if (!$libelle) {
            return new JsonResponse(['error' => 'Le libellé est requis'], 400);
        }

        $typePapier = new TypePapier();
        $typePapier->setLibelle($libelle);
        $em->persist($typePapier);
        $em->flush();

        return new JsonResponse(['id' => $typePapier->getId(), 'libelle' => $typePapier->getLibelle()]);
    }

    #[Route('/type-papier/{id}/edit', name: 'app_configuration_type_papier_edit', methods: ['POST'])]
    public function editTypePapier(TypePapier $typePapier, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $libelle = $request->request->get('libelle');
        if (!$libelle) {
            return new JsonResponse(['error' => 'Le libellé est requis'], 400);
        }

        $typePapier->setLibelle($libelle);
        $em->flush();

        return new JsonResponse(['id' => $typePapier->getId(), 'libelle' => $typePapier->getLibelle()]);
    }

    #[Route('/type-papier/{id}/delete', name: 'app_configuration_type_papier_delete', methods: ['POST'])]
    public function deleteTypePapier(TypePapier $typePapier, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($typePapier);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    // Type Rassemblement
    #[Route('/type-rassemblement/add', name: 'app_configuration_type_rassemblement_add', methods: ['POST'])]
    public function addTypeRassemblement(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $libelle = $request->request->get('libelle');
        if (!$libelle) {
            return new JsonResponse(['error' => 'Le libellé est requis'], 400);
        }

        $typeRassemblement = new TypeRassmblement();
        $typeRassemblement->setLibelle($libelle);
        $em->persist($typeRassemblement);
        $em->flush();

        return new JsonResponse(['id' => $typeRassemblement->getId(), 'libelle' => $typeRassemblement->getLibelle()]);
    }

    #[Route('/type-rassemblement/{id}/edit', name: 'app_configuration_type_rassemblement_edit', methods: ['POST'])]
    public function editTypeRassemblement(TypeRassmblement $typeRassemblement, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $libelle = $request->request->get('libelle');
        if (!$libelle) {
            return new JsonResponse(['error' => 'Le libellé est requis'], 400);
        }

        $typeRassemblement->setLibelle($libelle);
        $em->flush();

        return new JsonResponse(['id' => $typeRassemblement->getId(), 'libelle' => $typeRassemblement->getLibelle()]);
    }

    #[Route('/type-rassemblement/{id}/delete', name: 'app_configuration_type_rassemblement_delete', methods: ['POST'])]
    public function deleteTypeRassemblement(TypeRassmblement $typeRassemblement, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($typeRassemblement);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    // Status Bureau
    #[Route('/status-bureau/add', name: 'app_configuration_status_bureau_add', methods: ['POST'])]
    public function addStatusBureau(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $libelle = $request->request->get('libelle');
        if (!$libelle) {
            return new JsonResponse(['error' => 'Le libellé est requis'], 400);
        }

        $statusBureau = new StatusBureau();
        $statusBureau->setLibelle($libelle);
        $em->persist($statusBureau);
        $em->flush();

        return new JsonResponse(['id' => $statusBureau->getId(), 'libelle' => $statusBureau->getLibelle()]);
    }

    #[Route('/status-bureau/{id}/edit', name: 'app_configuration_status_bureau_edit', methods: ['POST'])]
    public function editStatusBureau(StatusBureau $statusBureau, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $libelle = $request->request->get('libelle');
        if (!$libelle) {
            return new JsonResponse(['error' => 'Le libellé est requis'], 400);
        }

        $statusBureau->setLibelle($libelle);
        $em->flush();

        return new JsonResponse(['id' => $statusBureau->getId(), 'libelle' => $statusBureau->getLibelle()]);
    }

    #[Route('/status-bureau/{id}/delete', name: 'app_configuration_status_bureau_delete', methods: ['POST'])]
    public function deleteStatusBureau(StatusBureau $statusBureau, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($statusBureau);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    // Fonction Bureau
    #[Route('/fonction-bureau/add', name: 'app_configuration_fonction_bureau_add', methods: ['POST'])]
    public function addFonctionBureau(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $libelle = $request->request->get('libelle');
        if (!$libelle) {
            return new JsonResponse(['error' => 'Le libellé est requis'], 400);
        }

        $fonctionBureau = new FonctionBureau();
        $fonctionBureau->setLibelle($libelle);
        $em->persist($fonctionBureau);
        $em->flush();

        return new JsonResponse(['id' => $fonctionBureau->getId(), 'libelle' => $fonctionBureau->getLibelle()]);
    }

    #[Route('/fonction-bureau/{id}/edit', name: 'app_configuration_fonction_bureau_edit', methods: ['POST'])]
    public function editFonctionBureau(FonctionBureau $fonctionBureau, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $libelle = $request->request->get('libelle');
        if (!$libelle) {
            return new JsonResponse(['error' => 'Le libellé est requis'], 400);
        }

        $fonctionBureau->setLibelle($libelle);
        $em->flush();

        return new JsonResponse(['id' => $fonctionBureau->getId(), 'libelle' => $fonctionBureau->getLibelle()]);
    }

    #[Route('/fonction-bureau/{id}/delete', name: 'app_configuration_fonction_bureau_delete', methods: ['POST'])]
    public function deleteFonctionBureau(FonctionBureau $fonctionBureau, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($fonctionBureau);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    // Frais Syndic
    #[Route('/frais-syndic/add', name: 'app_configuration_frais_syndic_add', methods: ['POST'])]
    public function addFraisSyndic(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $montant = $request->request->get('montant');
        if (!$montant) {
            return new JsonResponse(['error' => 'Le montant est requis'], 400);
        }

        $fraisSyndic = new FraisSyndic();
        $fraisSyndic->setMontant((float)$montant);
        $em->persist($fraisSyndic);
        $em->flush();

        return new JsonResponse(['id' => $fraisSyndic->getId(), 'montant' => $fraisSyndic->getMontant()]);
    }

    #[Route('/frais-syndic/{id}/edit', name: 'app_configuration_frais_syndic_edit', methods: ['POST'])]
    public function editFraisSyndic(FraisSyndic $fraisSyndic, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $montant = $request->request->get('montant');
        if (!$montant) {
            return new JsonResponse(['error' => 'Le montant est requis'], 400);
        }

        $fraisSyndic->setMontant((float)$montant);
        $em->flush();

        return new JsonResponse(['id' => $fraisSyndic->getId(), 'montant' => $fraisSyndic->getMontant()]);
    }

    #[Route('/frais-syndic/{id}/delete', name: 'app_configuration_frais_syndic_delete', methods: ['POST'])]
    public function deleteFraisSyndic(FraisSyndic $fraisSyndic, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($fraisSyndic);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    // Fonction Employé
    #[Route('/fonction-employe/add', name: 'app_configuration_fonction_employe_add', methods: ['POST'])]
    public function addFonctionEmploye(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $libelle = $request->request->get('libelle');
        if (!$libelle) {
            return new JsonResponse(['error' => 'Le libellé est requis'], 400);
        }

        $fonctionEmploye = new FonctionEmploye();
        $fonctionEmploye->setLibelle($libelle);
        $em->persist($fonctionEmploye);
        $em->flush();

        return new JsonResponse(['id' => $fonctionEmploye->getId(), 'libelle' => $fonctionEmploye->getLibelle()]);
    }

    #[Route('/fonction-employe/{id}/edit', name: 'app_configuration_fonction_employe_edit', methods: ['POST'])]
    public function editFonctionEmploye(FonctionEmploye $fonctionEmploye, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $libelle = $request->request->get('libelle');
        if (!$libelle) {
            return new JsonResponse(['error' => 'Le libellé est requis'], 400);
        }

        $fonctionEmploye->setLibelle($libelle);
        $em->flush();

        return new JsonResponse(['id' => $fonctionEmploye->getId(), 'libelle' => $fonctionEmploye->getLibelle()]);
    }

    #[Route('/fonction-employe/{id}/delete', name: 'app_configuration_fonction_employe_delete', methods: ['POST'])]
    public function deleteFonctionEmploye(FonctionEmploye $fonctionEmploye, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($fonctionEmploye);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}
