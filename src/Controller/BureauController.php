<?php

namespace App\Controller;

use App\Entity\Bureau;
use App\Form\BureauType;
use App\Repository\BureauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bureau')]
class BureauController extends AbstractController
{
    #[Route('/', name: 'app_bureau_index', methods: ['GET'])]
    public function index(BureauRepository $bureauRepository): Response
    {
        return $this->render('bureau/index.html.twig', [
            'bureaus' => $bureauRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bureau_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bureau = new Bureau();
        $form = $this->createForm(BureauType::class, $bureau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bureau);
            $entityManager->flush();

            return $this->redirectToRoute('app_bureau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bureau/new.html.twig', [
            'bureau' => $bureau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bureau_show', methods: ['GET'])]
    public function show(Bureau $bureau): Response
    {
        return $this->render('bureau/show.html.twig', [
            'bureau' => $bureau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bureau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bureau $bureau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BureauType::class, $bureau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bureau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bureau/edit.html.twig', [
            'bureau' => $bureau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bureau_delete', methods: ['POST'])]
    public function delete(Request $request, Bureau $bureau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bureau->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bureau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bureau_index', [], Response::HTTP_SEE_OTHER);
    }
}
