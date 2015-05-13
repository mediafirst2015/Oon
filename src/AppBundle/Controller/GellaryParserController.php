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

class GellaryParserController extends Controller
{

    public $filePath = '/var/www/navigator/current/web/';
//    public $filePath = '/var/www/map/web/';

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
     * @Route("/parserGellary/1")
     */
    public function parserGellary1Action(){
        $this->filePath = $this->filePath.'G1.XLSX';
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Gallery');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Gallery');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;


        while(true){
            if ($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue() == ''){
                break;
            }
            $banner = new Banner();
            $banner->setCompany($company);
            $banner->setAdrs($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setTitle($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setBody($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setSide($this->getSide($phpExcelObject->setActiveSheetIndex(0)->getCell('D'.$num)->getValue()));
            $banner->setCity( ($phpExcelObject->setActiveSheetIndex(0)->getCell('A'.$num)->getValue() == 'Москва' ? 'Москва' : 'Московская область') );

            $banner->setGid($phpExcelObject->setActiveSheetIndex(0)->getCell('F'.$num)->getValue());
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('N'.$num)->getValue()));
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('O'.$num)->getValue()));
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getValue())*0.82);
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getValue()));
            $banner->setPriceDeploy(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('M'.$num)->getValue()));
            $banner->setTaxType('НДС (18%)');
            $banner->setFormat(($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue() == 'Биллборд 3x6 [BB]' ? '3x6' : 'big' ));
            $banner->setType($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue());
            $banner->setArea($phpExcelObject->setActiveSheetIndex(0)->getCell('U'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(0)->getCell('C'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(0)->getCell('C'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getHyperlink()->getUrl());

            $banner->setLongitude(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('AA'.$num)->getValue()));
            $banner->setLatitude(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('Z'.$num)->getValue()));
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return new Response('открылось');
    }

    /**
     * @Route("/parserGellary/2")
     */
    public function parserGellary2Action(){
        $this->filePath = $this->filePath.'G2.XLSX';
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Gallery');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Gallery');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;


        while(true){
            if ($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue() == ''){
                break;
            }
            $banner = new Banner();
            $banner->setCompany($company);
            $banner->setAdrs($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setTitle($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setBody($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setSide($this->getSide($phpExcelObject->setActiveSheetIndex(0)->getCell('D'.$num)->getValue()));
            $banner->setCity( ($phpExcelObject->setActiveSheetIndex(0)->getCell('A'.$num)->getValue() == 'Москва' ? 'Москва' : 'Московская область') );

            $banner->setGid($phpExcelObject->setActiveSheetIndex(0)->getCell('F'.$num)->getValue());
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('K'.$num)->getValue()));
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('L'.$num)->getValue()));
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('I'.$num)->getValue()));
            $banner->setPriceDeploy(0);
            $banner->setTaxType('НДС (18%)');
            $banner->setFormat('small');
            $banner->setType($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue());
            $banner->setArea($phpExcelObject->setActiveSheetIndex(0)->getCell('X'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(0)->getCell('C'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(0)->getCell('C'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getHyperlink()->getUrl());

            $url = 'http://geocode-maps.yandex.ru/1.x/?geocode='.urlencode($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $content = file_get_contents($url);
            $XmlObj = simplexml_load_string($content);
            if (isset($XmlObj->GeoObjectCollection->featureMember->GeoObject->Point->pos)){
                $pos['x'] = explode(' ',$XmlObj->GeoObjectCollection->featureMember->GeoObject->Point->pos)[1];
                $pos['y'] = explode(' ',$XmlObj->GeoObjectCollection->featureMember->GeoObject->Point->pos)[0];
            }else{
                $pos['x'] = 0;
                $pos['y'] = 0;
            }

            $banner->setLongitude($pos['y']);
            $banner->setLatitude($pos['x']);
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
            if ($num % 50 == 0){
                sleep(rand(1,5));
            }
        }

        return new Response('открылось');
    }


    /**
     * @Route("/parserVera/images")
     */
    public function parseImageAction(){
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->findByCompany($company);

        foreach ( $banners as $b ){
            $link = $b->getLink();
            $image = str_replace('http://www.olymp.ru/index.php?op=sidedb&keyid=','',$link);
            preg_match ('%\d+%', $image, $matches);
            $image = $matches[0];
            $image = 'http://olymp.ru/pic/standsKID/'.$image.'.jpg';
            $b->setImg($image);
            $em->flush($b);
        }

        return new Response('Все');
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
        $link = str_replace('ш=','',$link);
        $link = str_replace('д=','',$link);
        $link = explode(',',$link);
        return $link;
    }
}