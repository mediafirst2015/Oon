<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BannerRepository extends EntityRepository
{
    public function filter($id,$city,$area,$formatS,$formatM,$formatL,$formatSB,$type,$light,$grpMin,$grpMax,$otsMin,$otsMax,$priceMin,$priceMax)
    {

        $qb = $this->_em->createQueryBuilder();
        $qb->select('b')
            ->from('AppBundle:Banner','b')
            ->where('b.enabled = 1');
        if ($id != null){
            $qb->andWhere("b.id = '$id'");
        }else{
            if ($area != null ){
                $qb->andWhere("b.area = '$area'");
            }

            if ($city != null){
                $qb->andWhere("b.city = '$city'");
            }

            $format = null;
            if ($formatS != null && $formatS != 0){
                $format .=" b.format='small'  ";
            }
            if ($formatM != null && $formatM != 0){
                $format .= ($format == null ? '' : ' OR ')." b.format='3x6'  ";
            }
            if ($formatL != null && $formatL != 0){
                $format .= ($format == null ? '' : ' OR ')." b.format='big'  ";
            }
            if ($formatSB != null && $formatSB != 0){
                $format .= ($format == null ? '' : ' OR ')." b.format='cityboard'  ";
            }

            if ($format != null ){
                $qb->andWhere("( $format )");
            }
            if ($type != null ){
                $qb->andWhere("b.type = '$type'");
            }
            if ($light == 1 ){
                $qb->andWhere("b.light = 1 ");
            }
            if ($grpMin != null && $grpMax != null){
                $qb->andWhere("( b.grp >= $grpMin AND b.grp <= $grpMax )");
            }
            if ($otsMin != null && $otsMax != null){
                $qb->andWhere("( b.ots >= $otsMin AND b.ots <= $otsMax )");
            }
            if ($priceMin != null && $priceMax != null){
                $priceMin = $priceMin*1000;
                $priceMax = $priceMax*1000;
                $qb->andWhere("( b.price >= $priceMin AND b.price <= $priceMax )");
            }
        }

//        echo $qb->getQuery()->getSQL();
//        exit;
        return $qb->getQuery()->getResult();
    }

    public function filterHot($params, $page)
    {
        if ( $page == null){
            $page = 0;
        }
        $qb = $this->_em->createQueryBuilder();
        $qb->select('b')
            ->from('AppBundle:Banner','b')
            ->where('b.enabled = 1')
            ->andWhere('b.hot = 1');

        if ($params['area'] != null && $params['area'] != 0 && $params['area'] != '0'){
            $qb->andWhere("b.area = '".$params['area']."'");
        }

        if ($params['city'] != null){
            $qb->andWhere("b.city = '".$params['city']."'");
        }

        $format = null;
        if ($params['formatS'] != null && $params['formatS'] != 0){
            $format .=" b.format='small'  ";
        }
        if ($params['formatM'] != null && $params['formatM'] != 0){
            $format .= ($format == null ? '' : ' OR ')." b.format='3x6'  ";
        }
        if ($params['formatL'] != null && $params['formatL'] != 0){
            $format .= ($format == null ? '' : ' OR ')." b.format='big'  ";
        }
        if ($params['formatSB'] != null && $params['formatSB'] != 0){
            $format .= ($format == null ? '' : ' OR ')." b.format='cityboard'  ";
        }

        if ($format != null ){
            $qb->andWhere("( $format )");
        }

        $qb->setFirstResult($page)
            ->setMaxResults(4);


        return $qb->getQuery()->getResult();
    }
}