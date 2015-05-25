<?php

namespace AdminBundle\Parser;

use AppBundle\Entity\Banner;
use AppBundle\Entity\City;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Parser\NokogiriParser;

class GellaryParser extends MainParser
{

//    public $filePath = '/var/www/navigator/current/web/';
//    public $filePath = '/var/www/map/web/';

    /**
     * @Route("/parserGellary/1")
     */
    public function parserGellary1Action($hot = false){
//        $this->filePath = $this->filePath.'G1.XLSX';
        $em = $this->em;
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Gallery');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Gallery');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 2;


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
            $city = $this->em->getRepository('AppBundle:City')->findOneByTitle($phpExcelObject->setActiveSheetIndex(0)->getCell('A'.$num)->getValue());
            if ($city == null){
                $city = new City();
                $city->setTitle($phpExcelObject->setActiveSheetIndex(0)->getCell('A'.$num)->getValue());
                $em->persist($city);
                $em->flush($city);
                $em->refresh($city);
            }

            $banner->setCity($city);

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
            $banner->setImg($this->getImage($phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getHyperlink()->getUrl()));
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getHyperlink()->getUrl());
            if ($hot){
                $banner->setHot(true);
            }else{
                $banner->setHot(false);
            }
            $banner->setLongitude(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('AA'.$num)->getValue()));
            $banner->setLatitude(str_replace(',','.',$phpExcelObject->setActiveSheetIndex(0)->getCell('Z'.$num)->getValue()));
            $em->persist($banner);
            $em->flush($banner);
            $num ++;

            if ($num % 50 == 0){
                sleep(rand(1,3));
            }
        }

        return true;
    }

    /**
     * @Route("/parserGellary/2")
     */
    public function parserGellary2Action($hot = false){
//        $this->filePath = $this->filePath.'G2.XLSX';
        $em = $this->em;
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Gallery');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Gallery');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 2;


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
            $city = $this->em->getRepository('AppBundle:City')->findOneByTitle($phpExcelObject->setActiveSheetIndex(0)->getCell('A'.$num)->getValue());
            if ($city == null){
                $city = new City();
                $city->setTitle($phpExcelObject->setActiveSheetIndex(0)->getCell('A'.$num)->getValue());
                $em->persist($city);
                $em->flush($city);
                $em->refresh($city);
            }

            $banner->setCity($city);

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
            $banner->setImg($this->getImage($phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getHyperlink()->getUrl()));
            $banner->setLink($phpExcelObject->setActiveSheetIndex(0)->getCell('G'.$num)->getHyperlink()->getUrl());
            if ($hot){
                $banner->setHot(true);
            }else{
                $banner->setHot(false);
            }
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


    /**
     * @Route("/parserGellary/testImg")
     */
    public function getImage($link = 'http://www.gallerymedia.com/Services/FaceInfo.aspx?FaceId=49429FD59AE252CF6DD9--54654A4-GM'){
//        $html = file_get_contents($link);
        $html = $this->get_url($link);
        $parser  = new NokogiriParser($html);
        $parser = $parser->get('img')->toArray();
        $txt = 'http://www.gallerymedia.com/Services/'.$parser[2]['src'];
        return $txt;
    }

    public function get_url($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.17) Gecko/2009122116 Firefox/3.0.17");
        $headers = array
        (
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*;q=0.8',
            'Accept-Language: ru,en-us;q=0.7,en;q=0.3',
            'Accept-Charset: windows-1251, utf-8;q=0.7,*;q=0.7'
        );
//    'Accept-Encoding: deflate',  убрал, т.к. без сжатия проще парсить потом

        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_REFERER, "www.gallerymedia.com");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch); // выполняем запрос curl
        curl_close($ch);
        return $result;
    }
}