<?php

namespace AdminBundle\Parser;

use AppBundle\Entity\Banner;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;

class GemaParser extends MainParser
{


    /**
     * @Route("/parser45", name="all_export")
     */
    public function parserGema1Action($hot = false){
        $em = $this->em;
        $filePath = $this->filePath;


        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Гема');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Гема');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($filePath);
        $num = 9;

        $city = $em->getRepository('AppBundle:City')->findOneById(1);
        $city2 = $em->getRepository('AppBundle:City')->findOneById(2);
        while(true){
            if ($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue() == ''){
                break;
            }
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

            if ($hot){
                $banner->setHot(true);
            }else{
                $banner->setHot(false);
            }

            $banner = $this->setBanner($banner);
            $month = array(
                '2015-06-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('U'.$num)->getValue()),
                '2015-07-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('V'.$num)->getValue()),
                '2015-08-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('W'.$num)->getValue()),
                '2015-09-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('X'.$num)->getValue()),
                '2015-10-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('Y'.$num)->getValue()),
                '2015-11-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('Z'.$num)->getValue()),
                '2015-12-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('AA'.$num)->getValue()),
            );
            $this->refreshStatus($banner,$month, array('date' => '2015-06-01' , 'sale' => $hot));



            $num ++;

            if ($num % 50 == 0){
                sleep(rand(1,5));
            }
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