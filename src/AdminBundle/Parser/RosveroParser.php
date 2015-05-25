<?php
namespace AdminBundle\Parser;

ini_set('memory_limit', '-1');

use AppBundle\Entity\Banner;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;

class RosveroParser extends MainParser
{

//    public $filePath = '/var/www/navigator/current/web/RV.xls';
//    public $filePath = '/var/www/map/web/RV.xls';



    /**
     * @Route("/parserRasvero/1")
     */
    public function parserRasvero1Action()
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
            $banner->setSide($this->getSide($phpExcelObject->setActiveSheetIndex(0)->getCell('I' . $num)->getValue()));
            $banner->setCity($city);
            $banner->setGrp(str_replace(',', '.', $phpExcelObject->setActiveSheetIndex(0)->getCell('M' . $num)->getValue()));
            $banner->setOts(str_replace(',', '.', $phpExcelObject->setActiveSheetIndex(0)->getCell('N' . $num)->getValue()));
            $banner->setGid($phpExcelObject->setActiveSheetIndex(0)->getCell('C' . $num)->getValue());

            $banner->setPrice(str_replace(array(',', ''), array('.', ''), $phpExcelObject->setActiveSheetIndex(0)->getCell('R' . $num)->getValue()));
            $banner->setPrice2(str_replace(array(',', ''), array('.', ''), $phpExcelObject->setActiveSheetIndex(0)->getCell('T' . $num)->getValue()));
            $banner->setTaxType('НДС (18%)');

            $banner->setFormat('small');
            $banner->setType(
                $phpExcelObject->setActiveSheetIndex(0)->getCell('J' . $num)->getValue().' '.
                $phpExcelObject->setActiveSheetIndex(0)->getCell('K' . $num)->getValue().' '.
                ' ( '.$phpExcelObject->setActiveSheetIndex(0)->getCell('H' . $num)->getValue().' ) '
            );

            $banner->setArea($phpExcelObject->setActiveSheetIndex(0)->getCell('G' . $num)->getValue());
            $banner->setLight(0);

            $banner->setImg($this->getPhoto($phpExcelObject->setActiveSheetIndex(0)->getCell('P'.$num)->getValue()));

            $banner->setLink(0);


//            $url = substr ($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue(), 0, strrpos($phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue(), '.'));
            $url = $phpExcelObject->setActiveSheetIndex(0)->getCell('E'.$num)->getValue();
//            if ($url == ''){
//                echo '<br /><br />'.$num;
//                break;
//            }
            $url = 'http://geocode-maps.yandex.ru/1.x/?geocode='.urlencode($url);
            echo $url.'<br />';
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

}
