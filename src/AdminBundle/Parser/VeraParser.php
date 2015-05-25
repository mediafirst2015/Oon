<?php

namespace AdminBundle\Parser;

ini_set('memory_limit', '-1');

use AdminBundle\Parser\MainParser;
use AppBundle\Entity\Banner;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;

class VeraParser extends MainParser
{

    /**
     * @Route("/parserVera/1")
     */
    public function parserVera1Action($hot = false){
        $em = $this->em;
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Вера Олимп');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;

        $city = $em->getRepository('AppBundle:City')->findOneById(1);
        while(true){
            if ($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue() == ''){
                break;
            }
            $banner = new Banner();
            $banner->setCompany($company);
            $banner->setAdrs(explode("\n",$phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue())[0]);
            $banner->setTitle(explode("\n",$phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue())[0]);
            $banner->setBody($phpExcelObject->setActiveSheetIndex(0)->getCell('B'.$num)->getValue());
            $banner->setSide($phpExcelObject->setActiveSheetIndex(0)->getCell('C'.$num)->getValue());
            $banner->setCity($city);
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(0)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('L'.$num)->getValue()));
            $banner->setFormat('3x6');
            $banner->setType('3x6');
            $banner->setArea($phpExcelObject->setActiveSheetIndex(0)->getCell('R'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(0)->getCell('P'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(0)->getCell('P'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(0)->getCell('Q'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            if ($hot){
                $banner->setHot(true);
            }else{
                $banner->setHot(false);
            }
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return true;
    }


    /**
     * @Route("/parserVera/2")
     */
    public function parserVera2Action($hot = false){
        $em = $this->em;
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
        $company = new Company();
        $company->setTitle('Вера Олимп');
        $em->persist($company);
        $em->flush($company);
        $em->refresh($company);
        }

        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;
        $city = $em->getRepository('AppBundle:City')->findOneById(1);

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
            $banner->setCity($city);
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(1)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(1)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(1)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(1)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(1)->getCell('L'.$num)->getValue()));
            $banner->setFormat('big');
            $banner->setType($phpExcelObject->setActiveSheetIndex(1)->getCell('M'.$num)->getValue());
            $banner->setArea($phpExcelObject->setActiveSheetIndex(1)->getCell('R'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(1)->getCell('P'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(1)->getCell('P'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(1)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(1)->getCell('Q'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            if ($hot){
                $banner->setHot(true);
            }else{
                $banner->setHot(false);
            }
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return true;
    }


    /**
     * @Route("/parserVera/3")
     */
    public function parserVera3Action($hot = false){
        $em = $this->em;
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Вера Олимп');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;
        $city = $em->getRepository('AppBundle:City')->findOneById(1);

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
            $banner->setCity($city);
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(2)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(2)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(2)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(2)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(2)->getCell('M'.$num)->getValue()));
            $banner->setFormat('cityboard');
            $banner->setType($phpExcelObject->setActiveSheetIndex(2)->getCell('N'.$num)->getValue());
            $banner->setArea($phpExcelObject->setActiveSheetIndex(2)->getCell('S'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(2)->getCell('Q'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(2)->getCell('Q'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(2)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(2)->getCell('R'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            if ($hot){
                $banner->setHot(true);
            }else{
                $banner->setHot(false);
            }
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return true;
    }

    /**
     * @Route("/parserVera/4")
     */
    public function parserVera4Action($hot = false){
        $em = $this->em;
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Вера Олимп');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }


        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;
        $city = $em->getRepository('AppBundle:City')->findOneById(1);

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
            $banner->setCity($city);
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(3)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(3)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(3)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(3)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(3)->getCell('M'.$num)->getValue()));
            $banner->setFormat('small');
            $banner->setType($phpExcelObject->setActiveSheetIndex(3)->getCell('N'.$num)->getValue());
            $banner->setArea($phpExcelObject->setActiveSheetIndex(3)->getCell('S'.$num)->getValue());
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(3)->getCell('Q'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(3)->getCell('Q'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(3)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(3)->getCell('R'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            if ($hot){
                $banner->setHot(true);
            }else{
                $banner->setHot(false);
            }
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return true;
    }


    /**
     * @Route("/parserVera/5")
     */
    public function parserVera5Action($hot = false){
        $em = $this->em;
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Вера Олимп');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }


        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;
        $city = $em->getRepository('AppBundle:City')->findOneById(1);

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
            $banner->setCity($city);
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
            $banner->setLink($phpExcelObject->setActiveSheetIndex(4)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(4)->getCell('R'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            if ($hot){
                $banner->setHot(true);
            }else{
                $banner->setHot(false);
            }
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return true;
    }

    /**
     * @Route("/parserVera/6")
     */
    public function parserVera6Action($hot = false){
        $em = $this->em;
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Вера Олимп');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }


        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 11;
        $city = $em->getRepository('AppBundle:City')->findOneById(2);

        while(true){
            if ($phpExcelObject->setActiveSheetIndex(5)->getCell('A'.$num)->getValue() == ''){
                break;
            }
            $banner = new Banner();
            $banner->setCompany($company);
            $banner->setAdrs(explode("\n",$phpExcelObject->setActiveSheetIndex(5)->getCell('B'.$num)->getValue())[0]);
            $banner->setTitle(explode("\n",$phpExcelObject->setActiveSheetIndex(5)->getCell('B'.$num)->getValue())[0]);
            $banner->setBody($phpExcelObject->setActiveSheetIndex(5)->getCell('B'.$num)->getValue());
            $banner->setSide($phpExcelObject->setActiveSheetIndex(5)->getCell('C'.$num)->getValue());
            $banner->setCity($city);
            $banner->setGrp(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(5)->getCell('E'.$num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(5)->getCell('F'.$num)->getValue());
            $banner->setPrice(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(5)->getCell('G'.$num)->getValue()));
            $banner->setPrice2(str_replace(array(',',''),array('.',''),$phpExcelObject->setActiveSheetIndex(5)->getCell('G'.$num)->getValue())*1.18);
            $banner->setTaxType('НДС (18%)');
            $banner->setOts(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(5)->getCell('M'.$num)->getValue()));
            $banner->setFormat('3x6');
            $banner->setType($phpExcelObject->setActiveSheetIndex(5)->getCell('N'.$num)->getValue());
            $banner->setArea(null);
            $banner->setLight(($phpExcelObject->setActiveSheetIndex(5)->getCell('Q'.$num)->getValue() == 'Да' || $phpExcelObject->setActiveSheetIndex(5)->getCell('Q'.$num)->getValue() == 'да' ? 1 : 0));
            $banner->setImg(null);
            $banner->setLink($phpExcelObject->setActiveSheetIndex(5)->getCell('J'.$num)->getHyperlink()->getUrl());
            $pos = $this->getPosition($phpExcelObject->setActiveSheetIndex(5)->getCell('R'.$num)->getValue());
            $banner->setLongitude($pos[1]);
            $banner->setLatitude($pos[0]);
            if ($hot){
                $banner->setHot(true);
            }else{
                $banner->setHot(false);
            }
            $em->persist($banner);
            $em->flush($banner);
            $num ++;
        }

        return true;
    }

    /**
     * @Route("/parserVera/images")
     */
    public function parseImageAction(){
        $em = $this->em;
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
        $banners = $em->getRepository('AppBundle:Banner')->findByCompany($company);

        foreach ( $banners as $b ){
            $link = $b->getLink();
            $image = str_replace('http://www.olymp.ru/index.php?op=sidedb&keyid=','',$link);
            preg_match ('%\d+%', $image, $matches);
            $image = $matches[0];
            $image = 'http://olymp.ru/pic/standsKID/'.$image.'.jpg';
            $b->setImg($image);
            $em->flush($b);
        }

        return true;
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