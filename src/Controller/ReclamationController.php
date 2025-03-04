<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\PdfService;
use Dompdf\Dompdf;  
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/reclamation')]
final class ReclamationController extends AbstractController
{
    #[Route(name: 'app_reclamation_index', methods: ['GET'])]
    public function index(
        ReclamationRepository $reclamationRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        // Fetch filter parameters from the request
        $etat = $request->query->get('etat');
        $categorie = $request->query->get('categorieRec');

        // Create the query builder to get reclamations with filtering options
        $queryBuilder = $reclamationRepository->createQueryBuilder('r');

        // Apply filters to the query if provided
        if ($etat) {
            $queryBuilder->andWhere('r.etat = :etat')
                ->setParameter('etat', $etat);
        }

        if ($categorie) {
            $queryBuilder->andWhere('r.categorie_rec LIKE :categorieRec')
                ->setParameter('categorieRec', '%' . $categorie . '%');
        }

        // Paginate the query results
        $pagination = $paginator->paginate(
            $queryBuilder, // The query to paginate
            $request->query->getInt('page', 1), // Current page number
            6 // Items per page
        );

        return $this->render('reclamation/index.html.twig', [
            'pagination' => $pagination, // Pass the paginated result to the template
        ]);
    }

    /*public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }*/

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setDateRec(new \DateTime());
            $reclamation->setEtat('En cours');
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->getPayload()->getString('_token'))) {
            foreach ($reclamation->getReponses() as $reponse) {
                $entityManager->remove($reponse);
            }
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('delete/{id}', name: 'app_reclamation_delete_delete')]
    public function deleteRec(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $reclamation = $entityManager->getRepository(Reclamation::class)->findOneBy(['id' => $id]);
        foreach ($reclamation->getReponses() as $reponse) {
            $entityManager->remove($reponse);
        }
        $entityManager->remove($reclamation);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('show-rep/{id}', name: 'app_reclamation_show_reponse_user', methods: ['GET'])]
    public function showReponse($id,EntityManagerInterface $entityManager,): Response
    {
        $reclamation = $entityManager->getRepository(Reclamation::class)->findOneBy(['id' => $id]);

        return $this->render('reclamation/show-reponse.html.twig', [
            'reclamation' => $reclamation,
            'reponse' => $reclamation->getReponses(),
        ]);
    }

    #[Route('/pdf/{id}', name: 'reclamation_pdf')]
    public function generatePdf(EntityManagerInterface $entityManager,$id): Response
    {
        // Assuming you fetch your reclamation data here
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);

        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        // Render HTML for the PDF
        $htmlContent = $this->renderView('pdf/reclamation.html.twig', [
            'reclamation' => $reclamation
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $pdfContent = $dompdf->output();

        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="reposne.pdf"');

        return $response;
    }
}
