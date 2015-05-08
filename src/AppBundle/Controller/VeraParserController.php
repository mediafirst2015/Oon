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

class VeraParserController extends Controller
{

//    public $filePath = '/var/www/navigator/current/web/Vera.xls';
    public $filePath = '/var/www/map/web/Vera.xls';
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
     * @Route("/parserVera/1")
     */
    public function parserVera1Action(){
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Вера Олимп');
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
            $banner->setAdrs(explode("\n",$phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue())[0]);
            $banner->setTitle(explode("\n",$phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue())[0]);
            $banner->setBody($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue());
            $banner->setSide($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue());
            $banner->setCity('Москва');
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(0)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('L'.$num)->getValue()));
            $banner->setFormat('3x6');
            $banner->setType('3x6');
            $banner->setArea($phpExcelObject->setActiveSheetIndex(0)->getCell('R'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(4)->getCell('P'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(4)->getCell('P'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getHyperlink()->getUrl());
            echo $phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getHyperlink()->getUrl();
//            exit;
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(0)->getCell('Q'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return new Response('открылось');
    }


    /**
     * @Route("/parserVera/2")
     */
    public function parserVera2Action(){
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
        $company = new Company();
        $company->setTitle('Вера Олимп');
        $em->persist($company);
        $em->flush($company);
        $em->refresh($company);
        }

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;


        while(true){
            if ($phpExcelObject->setActiveSheetIndex(1)->getCell('A'.$num)->getValue() == ''){
                break;
            }
            $banner = new Banner();
            $banner->setCompany($company);
            $banner->setAdrs(explode("\n",$phpExcelObject->setActiveSheetIndex(1)->getCell('B'.$num)->getValue())[0]);
            $banner->setTitle(explode("\n",$phpExcelObject->setActiveSheetIndex(1)->getCell('B'.$num)->getValue())[0]);
            $banner->setBody($phpExcelObject->setActiveSheetIndex(1)->getCell('B'.$num)->getValue());
            $banner->setSide($phpExcelObject->setActiveSheetIndex(1)->getCell('C'.$num)->getValue());
            $banner->setCity('Москва');
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(1)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(1)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(1)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(1)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(1)->getCell('L'.$num)->getValue()));
            $banner->setFormat('big');
            $banner->setType($phpExcelObject->setActiveSheetIndex(1)->getCell('M'.$num)->getValue());
            $banner->setArea($phpExcelObject->setActiveSheetIndex(1)->getCell('R'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(4)->getCell('P'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(4)->getCell('P'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(1)->getCell('Q'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return new Response('открылось');
    }


    /**
     * @Route("/parserVera/3")
     */
    public function parserVera3Action(){
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Вера Олимп');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;


        while(true){
            if ($phpExcelObject->setActiveSheetIndex(2)->getCell('A'.$num)->getValue() == ''){
                break;
            }
            $banner = new Banner();
            $banner->setCompany($company);
            $banner->setAdrs(explode("\n",$phpExcelObject->setActiveSheetIndex(2)->getCell('B'.$num)->getValue())[0]);
            $banner->setTitle(explode("\n",$phpExcelObject->setActiveSheetIndex(2)->getCell('B'.$num)->getValue())[0]);
            $banner->setBody($phpExcelObject->setActiveSheetIndex(2)->getCell('B'.$num)->getValue());
            $banner->setSide($phpExcelObject->setActiveSheetIndex(2)->getCell('C'.$num)->getValue());
            $banner->setCity('Москва');
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(2)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(2)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(2)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(2)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(2)->getCell('M'.$num)->getValue()));
            $banner->setFormat('cityboard');
            $banner->setType($phpExcelObject->setActiveSheetIndex(2)->getCell('N'.$num)->getValue());
            $banner->setArea($phpExcelObject->setActiveSheetIndex(2)->getCell('S'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(4)->getCell('Q'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(4)->getCell('Q'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(2)->getCell('R'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return new Response('открылось');
    }

    /**
     * @Route("/parserVera/4")
     */
    public function parserVera4Action(){
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Вера Олимп');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }


        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;


        while(true){
            if ($phpExcelObject->setActiveSheetIndex(3)->getCell('A'.$num)->getValue() == ''){
                break;
            }
            $banner = new Banner();
            $banner->setCompany($company);
            $banner->setAdrs(explode("\n",$phpExcelObject->setActiveSheetIndex(3)->getCell('B'.$num)->getValue())[0]);
            $banner->setTitle(explode("\n",$phpExcelObject->setActiveSheetIndex(3)->getCell('B'.$num)->getValue())[0]);
            $banner->setBody($phpExcelObject->setActiveSheetIndex(3)->getCell('B'.$num)->getValue());
            $banner->setSide($phpExcelObject->setActiveSheetIndex(3)->getCell('C'.$num)->getValue());
            $banner->setCity('Москва');
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(3)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(3)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(3)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(3)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(3)->getCell('M'.$num)->getValue()));
            $banner->setFormat('small');
            $banner->setType($phpExcelObject->setActiveSheetIndex(3)->getCell('N'.$num)->getValue());
            $banner->setArea($phpExcelObject->setActiveSheetIndex(3)->getCell('S'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(4)->getCell('Q'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(4)->getCell('Q'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(3)->getCell('R'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return new Response('открылось');
    }


    /**
     * @Route("/parserVera/5")
     */
    public function parserVera5Action(){
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Вера Олимп');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }


        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;


        while(true){
            if ($phpExcelObject->setActiveSheetIndex(4)->getCell('A'.$num)->getValue() == ''){
                break;
            }
            $banner = new Banner();
            $banner->setCompany($company);
            $banner->setAdrs(explode("\n",$phpExcelObject->setActiveSheetIndex(4)->getCell('B'.$num)->getValue())[0]);
            $banner->setTitle(explode("\n",$phpExcelObject->setActiveSheetIndex(4)->getCell('B'.$num)->getValue())[0]);
            $banner->setBody($phpExcelObject->setActiveSheetIndex(4)->getCell('B'.$num)->getValue());
            $banner->setSide($phpExcelObject->setActiveSheetIndex(4)->getCell('C'.$num)->getValue());
            $banner->setCity('Москва');
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(4)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(4)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(4)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(4)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(4)->getCell('M'.$num)->getValue()));
            $banner->setFormat('small');
            $banner->setType($phpExcelObject->setActiveSheetIndex(4)->getCell('N'.$num)->getValue());
            $banner->setArea($phpExcelObject->setActiveSheetIndex(4)->getCell('S'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(4)->getCell('Q'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(4)->getCell('Q'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(4)->getCell('R'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return new Response('открылось');
    }

    /**
     * @Route("/parserVera/6")
     */
    public function parserVera6Action(){
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Вера Олимп');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }


        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;


        while(true){
            if ($phpExcelObject->setActiveSheetIndex(4)->getCell('A'.$num)->getValue() == ''){
                break;
            }
            $banner = new Banner();
            $banner->setCompany($company);
            $banner->setAdrs(explode("\n",$phpExcelObject->setActiveSheetIndex(4)->getCell('B'.$num)->getValue())[0]);
            $banner->setTitle(explode("\n",$phpExcelObject->setActiveSheetIndex(4)->getCell('B'.$num)->getValue())[0]);
            $banner->setBody($phpExcelObject->setActiveSheetIndex(4)->getCell('B'.$num)->getValue());
            $banner->setSide($phpExcelObject->setActiveSheetIndex(4)->getCell('C'.$num)->getValue());
            $banner->setCity('Московская область');
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(4)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(4)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(4)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(4)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(4)->getCell('M'.$num)->getValue()));
            $banner->setFormat('3x6');
            $banner->setType($phpExcelObject->setActiveSheetIndex(4)->getCell('N'.$num)->getValue());
            $banner->setArea(null);
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(4)->getCell('Q'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(4)->getCell('Q'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(4)->getCell('R'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
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