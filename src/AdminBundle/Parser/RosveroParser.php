<?php
namespace AdminBundle\Parser;

ini_set('memory_limit', '-1');

use AppBundle\Entity\Banner;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;

class RosveroParser extends MainParser
{

     /**
     * @Route("/parserRasvero/1")
     */
    public function parserRasvero1Action($hot = false)
    {
        $em = $this->em;
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Расверо');
        if ($company == null) {
            $company = new Company();
            $company->setTitle('Расверо');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 7;
        $city = $em->getRepository('AppBundle:City')->findOneById(1);

        while (true) {
            if ($phpExcelObject->setActiveSheetIndex(0)->getCell('B' . $num)->getValue() == '') {
                break;
            }
            $banner = new Banner();
            $banner->setCompany($company);
            $banner->setAdrs(explode("\n", $phpExcelObject->setActiveSheetIndex(0)->getCell('E' . $num)->getValue())[0]);
            $banner->setTitle(explode("\n", $phpExcelObject->setActiveSheetIndex(0)->getCell('E' . $num)->getValue())[0]);
            $banner->setBody($phpExcelObject->setActiveSheetIndex(0)->getCell('E' . $num)->getValue());
            $banner->setSide($phpExcelObject->setActiveSheetIndex(0)->getCell('H' . $num)->getValue());
            $banner->setCity($city);
            $banner->setGrp(str_replace(',', '.', $phpExcelObject->setActiveSheetIndex(0)->getCell('L' . $num)->getValue()));
            $banner->setOts(str_replace(',', '.', $phpExcelObject->setActiveSheetIndex(0)->getCell('M' . $num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(0)->getCell('K' . $num)->getValue());

            $banner->setPrice(str_replace(array(',', ''), array('.', ''), $phpExcelObject->setActiveSheetIndex(0)->getCell('R' . $num)->getValue()*0.82));
            $banner->setPrice2(str_replace(array(',', ''), array('.', ''), $phpExcelObject->setActiveSheetIndex(0)->getCell('R' . $num)->getValue()));
            $banner->setTaxType('НДС (18%)');

            $banner->setFormat('small');
            $banner->setType(
                $phpExcelObject->setActiveSheetIndex(0)->getCell('I' . $num)->getValue().' '.
                $phpExcelObject->setActiveSheetIndex(0)->getCell('J' . $num)->getValue().' '.
                ' ( '.$phpExcelObject->setActiveSheetIndex(0)->getCell('G' . $num)->getValue().' ) '
            );

            $banner->setArea($phpExcelObject->setActiveSheetIndex(0)->getCell('F' . $num)->getValue());
            $banner->setLight(0);

            $banner->setImg($this->getPhoto($phpExcelObject->setActiveSheetIndex(0)->getCell('O'.$num)->getValue()));

            $banner->setLink(0);

            $url = $phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue();
            $url = 'http://geocode-maps.yandex.ru/1.x/?geocode='.urlencode($url);

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


//            $banner = $this->setBanner($banner);
//            $month = array(
//                '2015-08-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('S'.$num)->getValue()),
//                '2015-09-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('T'.$num)->getValue()),
//                '2015-10-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('U'.$num)->getValue()),
//                '2015-11-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('V'.$num)->getValue()),
//                '2015-12-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('W'.$num)->getValue()),
////                '2015-12-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('X'.$num)->getValue()),
////                '2015-12-01' => $this->getStatus($phpExcelObject->setActiveSheetIndex(0)->getCell('Y'.$num)->getValue()),
//            );
//            $this->refreshStatus($banner,$month, array('date' => '2015-06-01' , 'sale' => $hot));

//            if ($hot){
//                $banner->setHot(true);
//            }else{
//                $banner->setHot(false);
//            }
            if (strripos($phpExcelObject->setActiveSheetIndex(0)->getCell('S' . $num)->getValue(),'Свободно') !== false){
                $banner->setHot(true);
            }

            $this->em->persist($banner);
            $this->em->flush($banner);

            $num ++;
            if ($num % 50 == 0){
                sleep(rand(1,5));
            }
        }

        return true;
    }

    public function getSide($side){
        $side = preg_replace('/\d/','',$side);
        if ($side == 'А' || $side == 'A'){
            return 'A';
        }else{
            return 'B';
        }
    }

    public function getPhoto($str){
        $str = str_replace('=HYPERLINK("','',$str);
        $str = str_replace('","Фото")','',$str);
        return $str;
    }

    public function getStatus($str){
        if ( strripos($str,'Продано') !== false ) return '0';
        elseif ( strripos($str,'Свободно') !== false ) return '2';
        else return '1';
    }

}
