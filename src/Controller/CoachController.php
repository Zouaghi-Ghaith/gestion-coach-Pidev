<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\CoachFormType;
use App\Repository\CoachRepository;
use App\Entity\Coach;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;
use Gedmo\Tool\Wrapper\EntityWrapper;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Loggable\Entity\LogEntry;
use App\Service\UploaderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted; 


class CoachController extends AbstractController
{
    #[Route('/coach', name: 'app_coach')]
    public function index(): Response
    {
        return $this->render('coach/index.html.twig', [
            'controller_name' => 'CoachController',
        ]);
    }



    #[Route('/afficheS', name: 'afficheS')]
    public function afficheS(EntityManagerInterface $entityManager): Response
    {
    //recuperer le repositiry
    $r=$this->getDoctrine()->getRepository(Coach::Class);
    //utiliser la fonction finale
    $c=$r->findAll();
    return $this->render('coach/afficheS.html.twig', [
        'coach' => $c,        
    ]);
    }
    
    
    
    #[Route('/suppCoach/{id}', name: 'suppCoach')]
    public function suppcoach($id,CoachRepository $r,ManagerRegistry $doctrine): Response
    {
        //recupere la classroom a supprimer
        $coach=$r->find($id);
        //action et suppression
        $em=$doctrine->getManager();
        $em->remove($coach);
        $em->flush();
    
        return $this->redirectToRoute('afficheS',);
    }
    #[Route('ajouter', name: 'ajouter')]
public function addCoach(ManagerRegistry $doctrine, Request $req): Response {
    $em = $doctrine->getManager();
    $coach = new Coach();
    $form = $this->createForm(CoachFormType::class, $coach);
    $form->handleRequest($req);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($coach);
        $em->flush();
        return $this->redirectToRoute('afficheS');
    }

    return $this->renderForm('coach/ajouter.html.twig', ['f' => $form]);
}
    
    
    
        #[Route('modifier/{id}', name: 'modifier')]
        public function update( ManagerRegistry $doctrine, $id, Request $request,UploaderService $uploaderService): Response{
            $em= $doctrine->getManager();
            $coach=$doctrine->getRepository(Coach::class)->find($id);
            $form=$this->createForm(CoachFormType::class, $coach);
            $form->handleRequest($request);
            if ($form->isSubmitted()){
                $em->flush();
                return $this->redirectToRoute('afficheS');
            
            }
            return $this->renderForm('coach/ajouter.html.twig',['f'=>$form]);
        }

        #[Route('/AfficheFront', name: 'afficheFront')]
        public function afficheFront(): Response
        {
        //recuperer le repositiry
        $r=$this->getDoctrine()->getRepository(Coach::Class);
        //utiliser la fonction finale
        $c=$r->findAll();
        return $this->render('accueil/index.html.twig', [
            'coach' => $c,
            
        ]);
        }

        public function searchCoaches(Request $request, CoachRepository $coachRepository)
        {
            $criteria = [
                'min_age' => $request->query->get('min_age')
            ];
        
            $coaches = $coachRepository->findCoachesByCriteria($criteria);
        
            $averageAge = $coachRepository->getAverageAge();
        
            // Do something with the coaches and the average age...
        
            // ...
            return $this->render('afficheS.html.twig', [
                'averageAge' => $averageAge,
            ]);
        }


        public function countCoachesAboveAge(Request $request, CoachRepository $coachRepository)
        {
            $coaches = $coachRepository->findCoachesAboveAge(30);
            $count = count($coaches);
        
            // Do something with the count...
        
            return $this->render('count.html.twig', [
                'count' => $count,
            ]);
        }

        /**
 * @Route("/coach/upload-image", name="coach_upload_image")
 * @IsGranted("ROLE_ADMIN")
 */
public function uploadImage(Request $request): Response
{
    // Get the coach object
    $coachId = $request->request->get('coach_id');
    $coach = $this->getDoctrine()->getRepository(Coach::class)->find($coachId);

    // Upload the image
    $imageFile = $request->files->get('image_file');
    $coach->setImageFile($imageFile);
    $em = $this->getDoctrine()->getManager();
    $em->persist($coach);
    $em->flush();

    // Return the response
    return new JsonResponse(['success' => true]);
}



        

}




 
     

    
    
    


















