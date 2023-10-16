<?php

namespace App\Repository;

use App\Entity\Plan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plan>
 *
 * @method Plan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plan[]    findAll()
 * @method Plan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plan::class);
    }

    public function save(Plan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Plan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function statistiquePlace(){
        $entityManager=$this->getEntityManager();
        $query=$entityManager->createQuery("SELECT COUNT(p)  FROM APP\Entity\Plan p ");
        $query1=$entityManager->createQuery("SELECT COUNT(p)   FROM APP\Entity\Plan p WHERE p.nbParticipant < 20");
        $query2=$entityManager->createQuery("SELECT COUNT(p)  FROM APP\Entity\Plan p WHERE p.nbParticipant >= 20 and p.nbParticipant < 50");
        $query3=$entityManager->createQuery("SELECT COUNT(p)   FROM APP\Entity\Plan p WHERE p.nbParticipant >=50 and p.nbParticipant < 70");
        $query4=$entityManager->createQuery("SELECT COUNT(p)  FROM APP\Entity\Plan p WHERE p.nbParticipant >=70");

            return array("<20"=>$query1->getResult()[0]['1']/$query->getResult()[0]['1'],
        "20-49"=>$query2->getResult()[0]['1']/$query->getResult()[0]['1'],
        "50-69"=>$query3->getResult()[0]['1']/$query->getResult()[0]['1'],
        "70+"=>$query4->getResult()[0]['1']/$query->getResult()[0]['1']
    );
}
    

//    /**
//     * @return Plan[] Returns an array of Plan objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Plan
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

