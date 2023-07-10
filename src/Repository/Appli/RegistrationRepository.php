<?php

namespace App\Repository\Appli;

use App\Entity\Appli\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<registration>
 *
 * @method Registration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Registration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Registration[]    findAll()
 * @method Registration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registration::class);
    }

    public function add(Registration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Registration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchRegistrationByUserAndBookrooms($bookroom, $user)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.seance', 'b')
            ->leftJoin('r.studient', 'm')
            ->Select('
                r.id,
                b.id as idbook,
                b.dateBookAt as dateBookAt,
                b.hourBookOpenAt as hourBookOpenAt,
                b.hourBookClosedAt as hourBookClosedAt,
                m.id as idMember
            ')
            ->andWhere('b.id = :bookroom')
            ->setParameter('bookroom', $bookroom)
            ->andWhere('m.id = :user')
            ->setParameter('user', $user)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return registration[] Returns an array of registration objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?registration
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
