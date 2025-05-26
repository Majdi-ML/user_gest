<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Form\AppartementType;
use App\Repository\AppartementRepository;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appartement')]
class AppartementController extends AbstractController
{
    #[Route('/', name: 'app_appartement_index', methods: ['GET'])]
    public function index(Request $request, AppartementRepository $appartementRepository): Response
    {
        // Récupérer les paramètres de filtrage
        $blocFilter = $request->query->get('bloc', ''); // Définir une valeur par défaut vide si rien n'est sélectionné
$etageFilter = $request->query->get('etage', ''); // Idem
$locataireFilter = $request->query->get('locataire', ''); // Idem
$proprietaireFilter = $request->query->get('proprietaire', ''); 
    
        // Critères de filtrage dynamiques
        $criteria = [];
        if ($blocFilter) {
            $criteria['bloc'] = $blocFilter;
        }
        if ($etageFilter) {
            $criteria['etage'] = $etageFilter;
        }
        if ($locataireFilter) {
            $criteria['locataire'] = $locataireFilter;
        }
        if ($proprietaireFilter) {
            $criteria['proprietaire'] = $proprietaireFilter;
        }
    
        // Récupérer les appartements loués (avec locataire)
        $appartementsLoues = $appartementRepository->findAppartementsLoues(
            $criteria, // vos critères supplémentaires
            'etage',   // champ de tri
            'ASC'      // ordre de tri
        );
    
        // Récupérer les appartements non loués (sans locataire)
        $appartementsNonLoues = $appartementRepository->findBy(
            array_merge($criteria, ['locataire' => null]),
            ['etage' => 'ASC']
        );
    
        // Récupérer les options pour les filtres
        $blocs = $appartementRepository->findDistinctValues('bloc');
        $etages = $appartementRepository->findDistinctValues('etage');
        $locataires = $appartementRepository->findDistinctLocataires(); // Récupère les locataires avec nom et prénom
        $proprietaires = $appartementRepository->findDistinctProprietaires(); // Récupère les propriétaires avec nom et prénom
    
        return $this->render('appartement/index.html.twig', [
            'appartementsLoues' => $appartementsLoues,
            'appartementsNonLoues' => $appartementsNonLoues,
            'blocFilter' => $blocFilter,
            'etageFilter' => $etageFilter,
            'locataireFilter' => $locataireFilter,
            'proprietaireFilter' => $proprietaireFilter,
            'blocs' => $blocs,
            'etages' => $etages,
            'locataires' => $locataires, // Passer les locataires à la vue
            'proprietaires' => $proprietaires, // Passer les propriétaires à la vue
        ]);
    }
    



    #[Route('/new', name: 'app_appartement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,PersonneRepository $personneRepository): Response
    {
        $appartement = new Appartement();
        $form = $this->createForm(AppartementType::class, $appartement, [
            'proprietaires' => $personneRepository->findProprietaires(),
            'locataires' => $personneRepository->findLocataires(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($appartement);
            $entityManager->flush();

            return $this->redirectToRoute('app_appartement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appartement/new.html.twig', [
            'appartement' => $appartement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appartement_show', methods: ['GET'])]
    public function show(Appartement $appartement): Response
    {
        return $this->render('appartement/show.html.twig', [
            'appartement' => $appartement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appartement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appartement $appartement, EntityManagerInterface $entityManager,PersonneRepository $personneRepository): Response
    {
        $form = $this->createForm(AppartementType::class, $appartement, [
            'proprietaires' => $personneRepository->findProprietaires(),
            'locataires' => $personneRepository->findLocataires(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_appartement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appartement/edit.html.twig', [
            'appartement' => $appartement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appartement_delete', methods: ['POST'])]
    public function delete(Request $request, Appartement $appartement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appartement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appartement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appartement_index', [], Response::HTTP_SEE_OTHER);
    }
}
