<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BannerRepository extends EntityRepository
{
    public function filter($id,$city,$area,$formatS,$formatM,$formatL,$formatSB,$type,$light,$grpMin,$grpMax,$otsMin,$otsMax,$priceMin,$priceMax, $sideA = null, $sideB = null, $gid = null, $hot = null)
    {

        $currentDate = new \DateTime();
        $currentDate  = $currentDate->modify('+1 month');
        $currentDate = $currentDate->format('Y-m').'-01 00:00:00';

        $qb = $this->_em->createQueryBuilder();
        $qb->select('b')
            ->from('AppBundle:Banner','b')
            ->leftJoin('b.city', 'c')
            ->leftJoin('AppBundle:Month', 'm',"WITH","m.banner = b.id AND m.date = '".$currentDate."'")
            ->where('b.enabled = 1');
        if ($id != null){
            $qb->andWhere("b.id = '$id'");
        }else{
            if ($area != null ){
                $qb->andWhere("b.area = '$area'");
            }

            if ($city != null){
                $qb->andWhere("c.title = '$city'");
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

            if ($sideA === '0' ){
                $qb->andWhere(" b.side NOT LIKE '%A%' ");
            }
            if ($sideB === '0' ){
                $qb->andWhere(" b.side NOT LIKE '%B%' ");
            }

            if ($gid != null ){
                $qb->andWhere(" b.gid = '".$gid."' ");
            }

            if ($hot == 1){
                $qb->andWhere('m.sale is not NULL AND m.sale != 0');
            }

        }

//        echo $qb->getQuery()->getSQL();
//        exit;
        return $qb->getQuery()->getResult();
    }

    public function filterHot($params, $page = null)
    {
        $currentDate = new \DateTime();
        $currentDate  = $currentDate->modify('+1 month');
        $currentDate = $currentDate->format('Y-m').'-01 00:00:00';
        $qb = $this->_em->createQueryBuilder();
        $qb->select('b')
            ->from('AppBundle:Banner','b')
            ->leftJoin('b.city', 'c')
            ->leftJoin('AppBundle:Month', 'm',"WITH","m.banner = b.id AND m.date = '".$currentDate."'")
            ->where('b.enabled = 1 AND m.sale is not NULL AND m.sale != 0');

        if ($params['area'] != null && $params['area'] != 0 && $params['area'] != '0'){
            $qb->andWhere("b.area = '".$params['area']."'");
        }

        if ($params['city'] != null){
            $qb->andWhere("c.title = '".$params['city']."'");
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

        if ($page != null){
            $qb->setFirstResult($page)
                ->setMaxResults(4);
        }

//        echo $qb->getQuery()->getSQL();
        return $qb->getQuery()->getResult();
    }

    public function findOldBanner($gid,$side,$companyId){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('b')
            ->from('AppBundle:Banner','b')
            ->leftJoin('b.company', 'c')
            ->where('b.gid = :gid')
            ->andWhere('b.side = :side')
            ->andWhere('c.id = :companyId')
            ->setParameters(array(
                ':gid' =>$gid,
                ':side' =>$side,
                ':companyId' =>$companyId,
                )
            );
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findBanners($str){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('b')
            ->from('AppBundle:Banner','b')
            ->where('b.enabled = 1')
            ->andWhere('b.offer = 0')
            ->andWhere('b.adrs LIKE :str')
            ->setParameters(array(
                    'str' => '%'.$str.'%',
                )
            );
        return $qb->getQuery()->getResult();
    }
}