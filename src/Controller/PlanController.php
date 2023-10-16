<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\PlanType;
use App\Repository\PlanRepository;
use App\Repository\CoachRepository;
use App\Entity\Plan;
use App\Entity\Coach;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class PlanController extends AbstractController
{
    #[Route('/plan', name: 'app_plan')]
    public function index(): Response
    {
        return $this->render('plan/index.html.twig', [
            'controller_name' => 'PlanController',
        ]);
    }
    #[Route('/affichePlan', name: 'affichePlan')]
    public function affichePlan(): Response
    {
    //recuperer le repositiry
    $r=$this->getDoctrine()->getRepository(plan::Class);
    //utiliser la fonction finale
    $c=$r->findAll();
    
    return $this->render('plan/affichePlan.html.twig', [
        
        'plan'=>$c
    ]);
    }
    
    
    
    #[Route('/suppPlan/{id}', name: 'suppPlan')]
    public function suppPlan($id,PlanRepository $r,ManagerRegistry $doctrine): Response
    {
        //recupere la classroom a supprimer
        $Plan=$r->find($id);
        //action et suppression
        $em=$doctrine->getManager();
        $em->remove($Plan);
        $em->flush();
    
        return $this->redirectToRoute('affichePlan',);
    }
    
    #[Route('ajouterPlan/{id}', name: 'ajouterPlan')]
public function addPlan(ManagerRegistry $doctrine, Request $request, int $id): Response
{
    $em = $doctrine->getManager();
    
    // Retrieve the Coach entity from the database
    $coach = $em->getRepository(Coach::class)->find($id);
    
    // Create a new Plan object and set the coach
    $plan = new Plan();
    $plan->setCoaches($coach);
    
    // Create the form and handle the request
    $form = $this->createForm(PlanType::class, $plan);
    $form->handleRequest($request);
    
    // Process the form submission
    if ($form->isSubmitted() && $form->isValid()) {
        // Persist the Plan object to the database
        $em->persist($plan);
        $em->flush();
        
        // Redirect to the plan list page
        return $this->redirectToRoute('affichePlan');
    }
    
    // Render the form
    return $this->renderForm('plan/ajouterPlan.html.twig', [
        'f' => $form,
        'coach' => $coach,
    ]);
}
    
    
    
    
     #[Route('modifierPlan/{id}', name: 'modifierPlan')]
        public function updatePlan( ManagerRegistry $doctrine, $id, Request $request): Response{
            $em= $doctrine->getManager();
            $plan=$doctrine->getRepository(Plan::class)->find($id);
            $form=$this->createForm(PlanType::class, $plan);
            $form->handleRequest($request);
            if ($form->isSubmitted()){
                $em->flush();
                return $this->redirectToRoute('affichePlan');
            
            }
            return $this->renderForm('plan/ajouterPlan.html.twig',['f'=>$form]);
        }
        #[Route('/PlanFront/{id}', name: 'PlanFront')]
        public function plans($id,PaginatorInterface $paginator,Request $request)
        {
            $plans = $this->getDoctrine()->getRepository(Plan::class)->findBy([
                'coaches' => $id,
            ]);
            $pagination = $paginator->paginate(
                $plans,
                $request->query->getInt('page', 1),
                1
            );
            return $this->render('accueil/planFront.html.twig', [
                'pagination' => $pagination,
                'plans'=>$plans
            ]);
        }
        
        #[Route('/reserve/{id}', name: 'reserve')]
    public function reserve($id, PlanRepository $r, ManagerRegistry $doctrine): Response
    {
        
        $plan = $r->find($id);
    
        if ($plan->getNbParticipant() <= 0) {
           
            return new Response('ce planning atteindre le nombre maximale de reservation.');
        }
    
       
        $plan->setNbParticipant($plan->getNbParticipant() - 1);
    
        
        $em = $doctrine->getManager();
        $em->flush();
    
        
        return new Response('La reservation est confirme');
    }

    #[Route('/stat', name: 'stat')]
    public function stat(): Response
    {
        return $this->render('plan/stat.html.twig', [
            'controller_name' => 'PlanController',
        ]);
    }


    #[Route('/stat', name: 'stat')]
    public function statistique(PlanRepository $repository): Response
    {
        $place=$repository->statistiquePlace();
        
        return $this->render('plan/stat.html.twig', [
            'place' => $place,
           
        ]);
    }

}
    
    