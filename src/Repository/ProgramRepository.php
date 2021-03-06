<?php

namespace App\Repository;

use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

//     public function findLikeName(string $name)
// {
//     $queryBuilder = $this->createQueryBuilder('p')
//         ->where('p.title LIKE :name')
//         ->setParameter('name', '%' . $name . '%')
//         ->orderBy('p.title', 'ASC')
//         ->getQuery();

//     return $queryBuilder->getResult();
// }

// public function DQLfindLikeName(string $name)
//     {
//         $em = $this->getEntityManager();
//         $query = $em->createQuery("SELECT p FROM App\Entity\Program p WHERE p.title LIKE '%$name%' ORDER BY p.title ASC");

//         return $query->execute();
//     }

public function findAllWithProgramsAndActors(string $name)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->join('p.actor', 'a')
            ->where('p.title LIKE :name')
            ->orWhere('a.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('p.title', 'ASC')
            ->getQuery();

        return $queryBuilder->getResult();
    }
}
