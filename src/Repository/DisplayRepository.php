<?php

namespace App\Repository;

use App\Entity\Display;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Display|null find($id, $lockMode = null, $lockVersion = null)
 * @method Display|null findOneBy(array $criteria, array $orderBy = null)
 * @method Display[]    findAll()
 * @method Display[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisplayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Display::class);
    }
}
