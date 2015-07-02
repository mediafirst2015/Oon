<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ReviewRepository extends EntityRepository
{
    public function findRandom($count)
    {
        $result = $this->createQueryBuilder('r')
            ->addSelect('RAND() as HIDDEN rand')
            ->where('r.enabled = 1')
            ->orderBy('rand','DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();

        return $result;
    }
}