<?php

namespace App\Controller;

use App\Entity\FonctionEmploye;
use App\Form\FonctionEmployeType;
use App\Repository\FonctionEmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fonction/employe')]
class FonctionEmployeController extends AbstractController
{
    #[Route('/', name: 'app_fonction_employe_index', methods: ['GET'])]
    public function index(FonctionEmployeRepository $fonctionEmployeRepository): Response
    {
        return $this->render('fonction_employe/index.html.twig', [
            'fonction_employes' => $fonctionEmployeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fonction_employe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fonctionEmploye = new FonctionEmploye();
        $form = $this->createForm(FonctionEmployeType::class, $fonctionEmploye);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fonctionEmploye);
            $entityManager->flush();

            return $this->redirectToRoute('app_fonction_employe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fonction_employe/new.html.twig', [
            'fonction_employe' => $fonctionEmploye,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fonction_employe_show', methods: ['GET'])]
    public function show(FonctionEmploye $fonctionEmploye): Response
    {
        return $this->render('fonction_employe/show.html.twig', [
            'fonction_employe' => $fonctionEmploye,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fonction_employe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FonctionEmploye $fonctionEmploye, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FonctionEmployeType::class, $fonctionEmploye);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fonction_employe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fonction_employe/edit.html.twig', [
            'fonction_employe' => $fonctionEmploye,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fonction_employe_delete', methods: ['POST'])]
    public function delete(Request $request, FonctionEmploye $fonctionEmploye, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fonctionEmploye->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fonctionEmploye);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fonction_employe_index', [], Response::HTTP_SEE_OTHER);
    }
}
