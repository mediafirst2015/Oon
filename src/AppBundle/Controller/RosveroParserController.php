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

class RosveroParserController extends Controller
{

//    public $filePath = '/var/www/navigator/current/web/RV.xls';
    public $filePath = '/var/www/map/web/RV.xls';

    public function getLetter($num)
    {
        switch ($num) {
            case 1:
                return 'A';
            case 2:
                return 'B';
            case 3:
                return 'C';
            case 4:
                return 'D';
            case 5:
                return 'E';
            case 6:
                return 'F';
            case 7:
                return 'G';
            case 8:
                return 'H';
            case 9:
                return 'I';
            case 10:
                return 'J';
            case 11:
                return 'K';
            case 12:
                return 'L';
            case 13:
                return 'M';
            case 14:
                return 'N';
            case 15:
                return 'O';
            case 16:
                return 'P';
            case 17:
                return 'Q';
            case 18:
                return 'R';
            case 19:
                return 'S';
            case 20:
                return 'T';
            case 21:
                return 'U';
            case 22:
                return 'V';
            case 23:
                return 'W';
            case 24:
                return 'X';
            case 25:
                return 'Y';
            case 26:
                return 'Z';
            default:
                return false;
        }
    }


    /**
     * @Route("/parserRasvero/1")
     */
    public function parserVera1Action()
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Расверо');
        if ($company == null) {
            $company = new Company();
            $company->setTitle('Расверо');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($this->filePath);
        $num = 7;
        $city = $this->getDoctrine()->getRepository('AppBundle:City')->findOneById(1);

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

            $banner->setImg($phpExcelObject->setActiveSheetIndex(0)->getCell('P'.$num)->getHyperlink()->getUrl());

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

        return new Response('открылось');
    }

    public function getSide($side){
        $side = preg_replace('/\d/','',$side);
        if ($side == 'А' || $side == 'A'){
            return 'A';
        }else{
            return 'B';
        }
    }
}
