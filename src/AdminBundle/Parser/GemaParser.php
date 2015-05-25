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


        while(true){
            if ($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue() == ''){
                break;
            }
            $banner = new Banner();

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

            if ($hot){
                $banner->setHot(true);
            }else{
                $banner->setHot(false);
            }

            $banner->setBody(' ');
//            if ($isNew == true){
            $em->persist($banner);
//            }
            $em->flush($banner);
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