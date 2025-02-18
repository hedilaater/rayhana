<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reponse')]
final class ReponseController extends AbstractController
{
    #[Route(name: 'app_reclamation_adminn_index', methods: ['GET'])]
    public function reclamation(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reponse/admin_reclamation.html.twig', [
            'reclamation' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_reclamation_show', methods: ['GET'])]
    public function showReclamationAdmin(Reclamation $reclamation): Response
    {
        return $this->render('reponse/show-reclamation-admin.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/rep_reclamation', name: 'app_reponse_reclamation_admin', methods: ['GET', 'POST'])]
    public function reponseReclamation(Request $request, EntityManagerInterface $entityManager, $id, MailService $mailService): Response
    {
        $reclamation = $entityManager->getRepository(Reclamation::class)->findOneBy(['id' => $id]);
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse)->remove('reclamation')->remove('date_rep')->remove('user');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $reclamation->setEtat('Finish');
            $reponse->setReclamation($reclamation);
            $reponse->setDateRep(new \DateTime());
            $entityManager->persist($reponse);
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $mailService->sendEmail(
                $reclamation->getEmailRec(), // Send to the user's email from reclamation
                $reclamation->getNameRec(),  // Recipient name
                'Réponse à votre réclamation', // Email Subject
                "<h1>Bonjour {$reclamation->getNameRec()},</h1>
                 <p>L'administrateur a répondu à votre réclamation.</p>
                 <p><strong>Message de l'admin :</strong> {$reponse->getContenu()}</p>
                 <p>Merci,</p>
                 <p>Votre équipe de support</p>"
            );
            

            return $this->redirectToRoute('app_reclamation_adminn_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/edit-reposne-reclamation.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/index',name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }
}
