<?php

namespace AdminBundle\Parser;

use AppBundle\Entity\Banner;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Entity\Log;
use Symfony\Component\Config\Definition\Exception\Exception;

class GemaParser extends MainParser
{


    /**
     * @Route("/parser-gema", name="parser-gema")
     */
    public function parserGema1Action($hot = false){
        $em = $this->em;
        $filePath = $this->filePath;

        $city = $em->getRepository('AppBundle:City')->findOneById(1);
        $city2 = $em->getRepository('AppBundle:City')->findOneById(2);

        $log = new Log();
        $log->setTitle('Поехали');
        $em->persist($log);
        $em->flush($log);

        $company = $em->getRepository('AppBundle:Company')->findOneById(1);
        if ($company == null){
            $log = new Log();
            $log->setTitle('Компания не нашлась');
            $em->persist($log);
            $em->flush($log);
            $company = new Company();
            $company->setTitle('Гема');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $log = new Log();
        $log->setTitle('Компания нашлась');
        $em->persist($log);
        $em->flush($log);

        $log = new Log();
        $log->setTitle($filePath);
        $em->persist($log);
        $em->flush($log);

        try{
            $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($this->filePath);
            $log = new Log();
            $log->setTitle('4');
            $em->persist($log);
            $em->flush($log);
        }catch (Exception $e){
            $log = new Log();
            $log->setTitle($e->getMessage());
            $em->persist($log);
            $em->flush($log);
        }
        $num = 9;

        $log = new Log();
        $log->setTitle('5');
        $em->persist($log);
        $em->flush($log);

        $log = new Log();
        $log->setTitle('Поехали в 2');
        $em->persist($log);
        $em->flush($log);
        while(true){
            $log = new Log();
            $log->setTitle('1');
            $em->persist($log);
            $em->flush($log);

            if ($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue() == ''){
                $log = new Log();
                $log->setTitle('икуфл');
                $em->persist($log);
                $em->flush($log);
                break;
            }
            $log = new Log();
            $log->setTitle('2');
            $em->persist($log);
            $em->flush($log);

            $banner = new Banner();

            $banner->setCompany($company);
            $banner->setArea($this->getArea($phpExcelObject->setActiveSheetIndex(0)->getCell('D'.$num)->getValue()));
            $banner->setAdrs($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setTitle($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setBody($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue());
            $banner->setSide($this->getSide($phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getValue()));
            if ($banner->getArea() == null){
                $banner->setCity($city2);
            }else{
                $banner->setCity($city);
            }
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(0)->getCell('H'.$num)->getValue() == 'Да' ? 1 : 0));
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('I'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(0)->getCell('C'.$num)->getValue());
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

//            if ($hot){
//                $banner->setHot(true);
//            }else{
                $banner->setHot(false);
//            }

            $log = new Log();
            $log->setTitle('save'.$banner->getAdrs());
            $em->persist($log);
            $em->flush($log);

            $em->persist($banner);
            $em->flush($banner);

//            $banner = $this->setBanner($banner);
//            $month = array(
//                '2015-06-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('U'.$num)->getValue()),
//                '2015-07-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('V'.$num)->getValue()),
//                '2015-08-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('W'.$num)->getValue()),
//                '2015-09-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('X'.$num)->getValue()),
//                '2015-10-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('Y'.$num)->getValue()),
//                '2015-11-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('Z'.$num)->getValue()),
//                '2015-12-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('AA'.$num)->getValue()),
//            );
//            $this->refreshStatus($banner,$month, array('date' => '2015-06-01' , 'sale' => $hot));



            $num ++;
//
//            if ($num % 50 == 0){
//                    $log = new Log();
//                    $log->setTitle('Синхронизация записи'.$banner->getAdrs());
//                    $em->persist($log);
//                    $em->flush($log);
//            }
        }

        return true;
    }

    public function getArea($ares){
        switch($ares){
            case 'Северный': return 'САО';
            case 'Западный': return 'ЗАО';
            case 'Восточный': return 'ВАО';
            case 'Южный': return 'ЮАО';
            case 'Центральный': return 'ЦАО';
            case 'Московская область': return null;
        }
        return null;
    }

    public function getSide($side){
        $side = str_replace('Сторона ','',$side);
        $side = str_replace('Б','B',$side);
        $side = str_replace('А','A',$side);

//        $side = preg_replace('/\d/','',$side);
//        if ($side == 'А'){
//            return 'A';
//        }else{
//            return 'B';
//        }
        return $side;
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

    public function getStatus($str){
        if ( strripos($str,'продано') !== false ) return '0';
        elseif ( strripos($str,'свободно') !== false ) return '2';
        elseif ( strripos($str,'в резерве') !== false ) return '1';
        else return '1';
    }
}