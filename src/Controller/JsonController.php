<?php

namespace App\Controller;
use App\Repository\CoachRepository;
use App\Repository\PlanRepository;
use App\Entity\Coach;
use App\Entity\Plan;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\Serializer\Annotation\Groups;

class JsonController extends AbstractController
{
    #[Route('/json', name: 'app_json')]
    public function index(): Response
    {
        return $this->render('json/index.html.twig', [
            'controller_name' => 'JsonController',
        ]);
    }


    

    #[Route("/Allcoach", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getCoaches(CoachRepository $repo, SerializerInterface $serializer)
    {
        $coach = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);


        
        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

            $json = $serializer->serialize($coach, 'json', ['groups' => "coachs"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }
    




    #[Route("/CoachbyId/{id}", name: "coach")]
    public function CoachId($id, NormalizerInterface $normalizer, CoachRepository $repo)
    {
        $coach = $repo->find($id);
        $coachNormalises = $normalizer->normalize($coach, 'json', ['groups' => "coachs"]);
        return new Response(json_encode($coachNormalises));
    }



    #[Route("/addCoachJSON/new", name: "addCoachJSON")]
    public function addCoachJSON(ManagerRegistry $doctrine, Request $req,   NormalizerInterface $Normalizer)
    {
        
        $em = $doctrine->getManager();
        $coach = new Coach();
        $coach->setNom($req->get('Nom'));
        $coach->setPrenom($req->get('Prenom'));
        $coach->setAge($req->get('Age'));;
        $coach->setSpecialite($req->get('specialite'));
        $coach->setEmail($req->get('email'));
        

        $em->persist($coach);
        $em->flush();

        $jsonContent = $Normalizer->normalize($coach, 'json', ['groups' => 'coachs']);
        return new Response(json_encode($jsonContent));
    }

    //http://127.0.0.1:8000/addCoachJSON/new?Nom="zz"&Prenom="zz"&Age=24&specialite="zaqs"&email=MasterMinds.name@esprit.tn»


    #[Route("updateCoachJSON/{id}", name: "updateCoachJSON")]
    public function updateCoachJSON(ManagerRegistry $doctrine, Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $doctrine->getManager();
        $coach = $em->getRepository(Coach::class)->find($id);
        $coach->setNom($req->get('Nom'));
        $coach->setPrenom($req->get('Prenom'));
        $coach->setAge($req->get('Age'));;
        $coach->setSpecialite($req->get('specialite'));
        $coach->setEmail($req->get('email'));


        $em->flush();

        $jsonContent = $Normalizer->normalize($coach, 'json', ['groups' => 'coachs']);
        return new Response("Coach a été modifié avec succéss!! " . json_encode($jsonContent));
    }
        //http://127.0.0.1:8000/updateCoachJSON/17?Nom="zaz"&Prenom="zaz"&Age=27&specialite="zaqs"&email=modified.name@esprit.tn»






    #[Route("deleteCoachJSON/{id}", name: "deleteCoachJSON")]
    public function deleteCoachJSON(ManagerRegistry $doctrine, Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $doctrine->getManager();
        $coach = $em->getRepository(Coach::class)->find($id);
        $em->remove($coach);
        $em->flush();
        $jsonContent = $Normalizer->normalize($coach, 'json', ['groups' => 'coachs']);
        return new Response("coach a été supp avec  success" . json_encode($jsonContent));
    }
    //http://127.0.0.1:8000/deleteCoachJSON/17



    #[Route("/AllPlan", name: "listP")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getPlans(PlanRepository $repo, SerializerInterface $serializer)
    {
        $plan = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);


        
        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

            $json = $serializer->serialize($plan, 'json', ['groups' => "plans"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }

}
