<?php

namespace AdminBundle\Parser;

use AppBundle\Entity\Banner;
use AppBundle\Entity\Month;

class MainParser{

    public $em;

    protected $filePath;

    protected $container;

    public function __construct(&$em, &$container,$path){
        $this->em = $em;
        $this->filePath = $path;
        $this->container = $container;
    }

    public function getLetter($num){
        switch ($num){
            case 1: return 'A';
            case 2: return 'B';
            case 3: return 'C';
            case 4: return 'D';
            case 5: return 'E';
            case 6: return 'F';
            case 7: return 'G';
            case 8: return 'H';
            case 9: return 'I';
            case 10: return 'J';
            case 11: return 'K';
            case 12: return 'L';
            case 13: return 'M';
            case 14: return 'N';
            case 15: return 'O';
            case 16: return 'P';
            case 17: return 'Q';
            case 18: return 'R';
            case 19: return 'S';
            case 20: return 'T';
            case 21: return 'U';
            case 22: return 'V';
            case 23: return 'W';
            case 24: return 'X';
            case 25: return 'Y';
            case 26: return 'Z';
            default: return false;
        }
    }

    public function setBanner(Banner &$banner){
        $oldBanner = $this->em->getRepository('AppBundle:Banner')->findOldBanner($banner->getGid(),$banner->getSide(),$banner->getCompany()->getId());
        if ($oldBanner){
            $oldBanner->setAdrs($banner->getAdrs());
            $oldBanner->setTitle($banner->getTitle());
            $oldBanner->setBody($banner->getBody());
            $oldBanner->setSide($banner->getSide());
            $oldBanner->setCity($banner->getCity());;
            $oldBanner->setGid($banner->getGid());
            $oldBanner->setGrp($banner->getGrp());
            $oldBanner->setOts($banner->getOts());
            $oldBanner->setPrice($banner->getPrice());
            $oldBanner->setPrice2($banner->getPrice2());
            $oldBanner->setPriceDeploy($banner->getPriceDeploy());
            $oldBanner->setTaxType($banner->getTaxType());
            $oldBanner->setFormat($banner->getFormat());
            $oldBanner->setType($banner->getType());
            $oldBanner->setArea($banner->getArea());
            $oldBanner->setLight($banner->getLight());
            $oldBanner->setImg($banner->getImg());
            $oldBanner->setLink($banner->getLight());
            $oldBanner->setLongitude($banner->getLongitude());
            $oldBanner->setLatitude($banner->getLatitude());
            $this->em->flush($oldBanner);
            $banner = $oldBanner;
        }else{
            $this->em->persist($banner);
            $this->em->flush($banner);
        }
        $this->em->refresh($banner);
        return $banner;
    }

    public function refreshStatus(Banner $banner, $status){
        $months = $banner->getMonths();
        foreach ($months as $m){
            $this->em->remove($m);
        }

        foreach ($status as $m => $s) {
            $date = new \DateTime($m.' 00:00:00');
            $month = new Month();
            $month->setBanner($banner);
            $month->setDate($date);
            $month->setStatus($s);
            $this->em->persist($month);
            $this->em->flush($month);
        }
    }

}
