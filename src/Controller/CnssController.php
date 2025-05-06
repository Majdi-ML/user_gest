<?php

namespace App\Controller;

use App\Entity\Cnss;
use App\Form\CnssType;
use App\Repository\CnssRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cnss')]
class CnssController extends AbstractController
{
    #[Route('/', name: 'app_cnss_index', methods: ['GET'])]
    public function index(CnssRepository $cnssRepository): Response
    {
        return $this->render('cnss/index.html.twig', [
            'cnsses' => $cnssRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cnss_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cnss = new Cnss();
        $form = $this->createForm(CnssType::class, $cnss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cnss);
            $entityManager->flush();

            return $this->redirectToRoute('app_cnss_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cnss/new.html.twig', [
            'cnss' => $cnss,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cnss_show', methods: ['GET'])]
    public function show(Cnss $cnss): Response
    {
        return $this->render('cnss/show.html.twig', [
            'cnss' => $cnss,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cnss_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cnss $cnss, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CnssType::class, $cnss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cnss_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cnss/edit.html.twig', [
            'cnss' => $cnss,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cnss_delete', methods: ['POST'])]
    public function delete(Request $request, Cnss $cnss, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cnss->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cnss);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cnss_index', [], Response::HTTP_SEE_OTHER);
    }
}
