<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BannerRepository extends EntityRepository
{
    public function filter($id, $street, $area,$formatS,$formatM,$formatL,$formatSB,$type,$light,$grpMin,$grpMax,$otsMin,$otsMax,$priceMin,$priceMax)
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
            if ($street != null ){
                $qb->andWhere("b.adrs LIKE '%$street%'");
            }

            $format = null;
            if ($formatS != null){
                $format .=" b.type='small'  ";
            }
            if ($formatM != null){
                $format .= ($format == null ? '' : ' OR ')." b.type='3x6'  ";
            }
            if ($formatL != null){
                $format .= ($format == null ? '' : ' OR ')." b.type='big'  ";
            }
            if ($formatL != null){
                $format .= ($format == null ? '' : ' OR ')." b.type='cityboard'  ";
            }

            if ($format != null ){
                $qb->andWhere("AND ($format)");
            }
            if ($type != null ){
                $qb->andWhere("b.type = '$type'");
            }
//        if ($light != null ){
//            $qb->andWhere("b.light = '$light'");
//        }
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

    public function filterHot($params)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('b')
            ->from('AppBundle:Banner','b')
            ->where('b.enabled = 1')
            ->andWhere('b.hot = 1');
        if ($params['area'] != null ){
            $qb->andWhere("b.area = '$params[area]'");
        }
        if ($params['street'] != null ){
            $qb->andWhere("b.adrs LIKE '%$params[street]%'");
        }
//        if ($params['packet'] != null ){
//            $qb->andWhere("b.enabled = '%$params[packet]%'");
//        }
        return $qb->getQuery()->getResult();
    }
}