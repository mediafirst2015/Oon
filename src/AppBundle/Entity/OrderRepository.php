<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    public function orderList($userId = null)
    {

        $qb = $this->_em->createQueryBuilder();

        if ($userId != null){
            $qb->select('o.created created, o.orderGroup orderGroup, o.status status, SUM(o2.price) fullPrice, o.id id')
                ->addSelect('c.lastName lastName, c.firstName firstName, c.surName surName, c.username username, c.phone phone')
                ->from('AppBundle:Order','o')
                ->leftJoin('o.client','c')
                ->leftJoin('AppBundle:Order','o2','WITH','o.orderGroup = o2.orderGroup')
                ->where('o.enabled = 1')
                ->andWhere('c.id = '.$userId)
                ->groupBy('o.orderGroup')
                ->orderBy('o.id', 'DESC');
        }else{
            $qb->select('o.created created, o.orderGroup orderGroup, o.status status, SUM(o2.price) fullPrice, o.id id')
                ->addSelect('c.lastName lastName, c.firstName firstName, c.surName surName, c.username username, c.phone phone')
                ->from('AppBundle:Order','o')
                ->leftJoin('o.client','c')
                ->leftJoin('AppBundle:Order','o2','WITH','o.orderGroup = o2.orderGroup')
                ->where('o.enabled = 1')
                ->groupBy('o.orderGroup')
                ->orderBy('o.id', 'DESC');
        }
//        echo $qb->getQuery()->getSQL();
        return $qb->getQuery()->getResult();
    }
}