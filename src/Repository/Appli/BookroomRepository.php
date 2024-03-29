<?php

namespace App\Repository\Appli;

use App\Entity\Appli\Bookroom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bookroom>
 *
 * @method Bookroom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookroom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookroom[]    findAll()
 * @method Bookroom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookroomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookroom::class);
    }

    public function add(Bookroom $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Bookroom $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function seance($course){
        return $this->createQueryBuilder('b')
            ->select('
                DISTINCT b.dateBookAt        
            ')
            ->andWhere('b.isActive = :isActive')
            ->setParameter('isActive', 1)
            ->andWhere('b.course = :course')
            ->setParameter('course', $course)
            ->orderBy('b.dateBookAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupére les infos d'une séance
     */
    public function bookroom($bookroom)
    {
        return $this->createQueryBuilder('b')
            ->select('b.forme, b.dateBookAt, b.uniq')
            ->andWhere('b.id = :bookroom')
            ->setParameter('bookroom', $bookroom)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Liste les séances par l'identifiant "uniq"
     */
    public function listByUniq($uniq)
    {
        return $this->createQueryBuilder('b')
            ->select('b.id, b.uniq')
            ->andWhere('b.uniq = :uniq')
            ->setParameter('uniq', $uniq)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return Bookroom[] Returns an array of Bookroom objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Bookroom
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
