<?php

namespace App\Controller;

use App\Entity\TypeRassmblement;
use App\Form\TypeRassmblementType;
use App\Repository\TypeRassmblementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/typerassmblement')]
class TypeRassmblementController extends AbstractController
{
    #[Route('/', name: 'app_type_rassmblement_index', methods: ['GET'])]
    public function index(TypeRassmblementRepository $typeRassmblementRepository): Response
    {
        return $this->render('type_rassmblement/index.html.twig', [
            'type_rassmblements' => $typeRassmblementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_rassmblement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeRassmblement = new TypeRassmblement();
        $form = $this->createForm(TypeRassmblementType::class, $typeRassmblement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeRassmblement);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_rassmblement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_rassmblement/new.html.twig', [
            'type_rassmblement' => $typeRassmblement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_rassmblement_show', methods: ['GET'])]
    public function show(TypeRassmblement $typeRassmblement): Response
    {
        return $this->render('type_rassmblement/show.html.twig', [
            'type_rassmblement' => $typeRassmblement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_rassmblement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeRassmblement $typeRassmblement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeRassmblementType::class, $typeRassmblement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_rassmblement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_rassmblement/edit.html.twig', [
            'type_rassmblement' => $typeRassmblement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_rassmblement_delete', methods: ['POST'])]
    public function delete(Request $request, TypeRassmblement $typeRassmblement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeRassmblement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeRassmblement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_rassmblement_index', [], Response::HTTP_SEE_OTHER);
    }
}
