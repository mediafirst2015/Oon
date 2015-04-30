<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Banner;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class GemaParserController extends Controller
{
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


    /**
     * @Route("/parser45", name="all_export")
     */
    public function parser45Action(){
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Гема');
//        $company->setTitle('Гема');
//        $em->persist($company);
//        $em->flush($company);
//        $em->refresh($company);

        $filePath = '/var/www/navigator/current/web/Gema.xls';
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($filePath);
        $num = 9;


        while(true){
            if ($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue() == ''){
                break;
            }
//            $banner = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getValue());
//            if (!$banner){
//                $isNew = true;
            $banner = new Banner();
//            }else{
//                $isNew = false;
//            }

            $banner->setCompany($company);
            $banner->setArea($this->getArea($phpExcelObject->setActiveSheetIndex(0)->getCell('D'.$num)->getValue()));
            $banner->setAdrs($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setTitle($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setSide($this->getSide($phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getValue()));
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(0)->getCell('H'.$num)->getValue() == 'Да' ? 1 : 0));
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('I'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getValue());
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('K'.$num)->getValue()));
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('L'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('M'.$num)->getValue()));
            $banner->setTaxType('НДС (18%)');
            $banner->setFormat($this->getFormat($phpExcelObject->setActiveSheetIndex(0)->getCell('N'.$num)->getValue()));
            $banner->setType($phpExcelObject->setActiveSheetIndex(0)->getCell('O'.$num)->getValue());
            $banner->setImg($phpExcelObject->setActiveSheetIndex(0)->getCell('Q'.$num)->getValue());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(0)->getCell('R'.$num)->getValue());
            $banner->setLongitude($pos[0]);
            $banner->setLatitude($pos[1]);
            $banner->setBody(' ');
//            if ($isNew == true){
            $em->persist($banner);
//            }
            $em->flush($banner);
            $num ++;
        }

        return new Response('открылось');
    }

    public function getArea($ares){
        switch($ares){
            case 'Северный': return 'САО';
            case 'Западный': return 'САО';
            case 'Восточный': return 'САО';
            case 'Южный': return 'САО';
            case 'Центральный': return 'САО';
        }
        return null;
    }

    public function getSide($side){
        $side = str_replace('Сторона ','',$side);
        $side = preg_replace('/\d/','',$side);
        if ($side == 'А'){
            return 'A';
        }else{
            return 'B';
        }
    }

    public function getFormat($format){
        if ($format == 'Биллборд'){
            return '3x6';
        }else{
            return 'big';
        }
    }

    public function getPosition($link){
        $link = str_replace('static-maps.yandex.ru/1.x/?l=map&pt=','',$link);
        $link = str_replace(',pmb&z=16','',$link);
        $link = explode(',',$link);
        return $link;
    }
}