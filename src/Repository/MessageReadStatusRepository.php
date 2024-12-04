<?php

namespace App\Repository;

use App\Entity\MessageReadStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageReadStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageReadStatus::class);
    }

    public function countUnreadMessages($commission, $user)
    {
        return $this->createQueryBuilder('mrs')
            ->select('COUNT(mrs.id)')
            ->where('mrs.commission = :commission')
            ->andWhere('mrs.user = :user')
            ->andWhere('mrs.isRead = false')
            ->setParameter('commission', $commission)
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
    
}

