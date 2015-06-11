<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Banner;
use AppBundle\Entity\City;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

set_time_limit(0);
ini_set("memory_limit","-1");

class ParserController extends Controller
{


    /**
     * @Route("/parser34/{type}", name="parser34")
     */
    public function parser34Action($type){
        if ($type == 1){
            $file = file_get_contents('m36.json');
        }else{
            $file = file_get_contents('mbig.json');
        }
        $array = json_decode($file,true);

        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Russoutdoor');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Russoutdoor');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }
        $area = array();
        foreach ($array['areasMap'][4] as $val){
            $area[$val['id']] = $val['code'];
        }
        foreach ($array['billboards'] as $val){
            if (isset($val['longtitude']) && isset($val['latitude']) && isset($val['address'])){
                $banner = new Banner();
                $city = $this->getDoctrine()->getRepository('AppBundle:City')->findOneByTitle($val['cityName']);
                if ($city == null){
                    $city = new City();
                    $city->setTitle($val['cityName']);
                    $em->persist($city);
                    $em->flush($city);
                    $em->refresh($city);
                }
                $banner->setCity($city);
                $banner->setCompany($company);
                $banner->setAdrs($val['address']);
                $banner->setTitle($val['address']);
                $banner->setGid($val['gid']);
                $banner->setGrp($val['grp']);
                $banner->setOts($val['ots']);
                $banner->setPrice((isset($val['price']) ? $val['price'] : 0));
                $banner->setPrice2((isset($val['price']) ? $val['price']*1.18 : 0));
                $banner->setLight($val['light']);
                $banner->setSide($val['side']);
                $desc = (isset($val['top']) ? $val['top'] : '').'<br />'.(isset($val['distance']) ? $val['distance'] : '' );
                $banner->setBody($desc);
                if ($type == 1){
                    $banner->setFormat('3x6');
                }else{
                    $banner->setFormat('big');
                }
                $banner->setLongitude($val['longtitude']);
                $banner->setLatitude($val['latitude']);
                $banner->setImg(str_replace('//','http://',$val['imageURL']));

                $banner->setArea((isset($val['subAreaId']) && isset($area[$val['subAreaId']]) ? $area[$val['subAreaId']] : null ));
                $em->persist($banner);
                $em->flush($banner);
            }else{
                echo $val['address'].'<br />';
                echo '<br />';
                print_r($val);
                echo '<br />';
                echo '<br />';
            }
        }

        exit;

    }


    /**
     * @Route("/mapcoder", name="mapcoder")
     * @Template()
     */
    public function geocoderAction(){

        $f = fopen('var.csv', 'r');
        echo '<table>';
        while(!feof($f)) {
            $str = fgets($f);
            $a = explode('|',$str);
            if (isset($a[6])){
                if (isset($a[7]) && isset($a[8]) && $a[7] != '' && $a[8]!= ''){
                    $a[8] = trim($a[8], " \n");

                    $obj = new Map2();
                    $obj->setTitle($a[2].', '.$a[3]);
                    $obj->setBody($a[2].', '.$a[3]);
                    $obj->setAdrs($a[6]);
                    $obj->setLongitude($a[7]);
                    $obj->setLatitude($a[8]);
                    $a[5] = str_replace(' ','%20',$a[5]);
                    $obj->setImg($a[5]);
                    $a[4] = str_replace(' ','',$a[4]);
                    $a[4] = str_replace('р.','',$a[4]);
                    $a[4] = str_replace(',','.',$a[4]);
                    $obj->setPrice($a[4]);
                    $this->getDoctrine()->getManager()->persist($obj);
                    $this->getDoctrine()->getManager()->flush($obj);
                    echo '<tr>';
                    echo '<td>'.$a[6].'</td>';
                    echo '<td>'.$a[7].'</td>';
                    echo '<td>'.$a[8].'</td>';
                    echo '<td>+</td>';
                    echo '</tr>';
                }else{
                    echo '<tr>';
                    echo '<td>'.$a[6].'</td>';
                    echo '<td></td>';
                    echo '<td></td>';
                    echo '<td>-</td>';
                    echo '</tr>';
                }
            }
        }
        echo '</table>';
        fclose($f);

//    ini_set('allow_url_fopen ','ON');
//     echo '<table>';
//     $w = 1;
//     foreach ( $ads as $val){
//         preg_match("/[\d]+/", $val,$match);
//         if (!empty($match)){
//             $url = 'http://geocode-maps.yandex.ru/1.x/?geocode='.urlencode($val);
//             $content = file_get_contents($url);
//             $XmlObj = simplexml_load_string($content);
//             echo '<tr>';
//             echo '<td>'.$val.'</td>';
//             echo '<td>'.explode(' ',$XmlObj->GeoObjectCollection->featureMember->GeoObject->Point->pos)[0].'</td>';
//             echo '<td>'.explode(' ',$XmlObj->GeoObjectCollection->featureMember->GeoObject->Point->pos)[1].'</td>';
//             echo '</tr>';
//         }else{
//          echo '<tr>';
//          echo '<td>'.$val.'</td>';
//          echo '<td></td>';
//          echo '<td></td>';
//          echo '</tr>';
//         }
//     }
//     echo '</table>';
        exit;
    }

    /**
     * @Route("/all-export", name="all_export")
     */
    public function exportAllAction(Request $request){
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        for($i = 1; $i<2000; $i ++){
            $phpExcelObject->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(30);
        }
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(40);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(20);
        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setLastModifiedBy("ООН")
            ->setTitle("Выгрузка")
            ->setSubject("выгрузка")
            ->setDescription("")
            ->setKeywords("")
            ->setCategory("");

        $area = $request->query->get('area');
        if ($area == 'null'){
            $area = null;
        }
        $format = $request->query->get('format');
        $type = $request->query->get('type');
        $light = $request->query->get('light');
        if ($light == 'on'){
            $light = 1;
        }else{
            $light = null;
        }
        $grpMin = $request->query->get('grpMin');
        $grpMax = $request->query->get('grpMax');

        $otsMin = $request->query->get('otsMin');
        $otsMax = $request->query->get('otsMax');


        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->filter($area,$format,$type,$light,$grpMin,$grpMax,$otsMin,$otsMax);


        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('A2', 'Округ');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('B2', 'Адрес');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('C2', 'Сторона');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('D2', 'GRP');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('E2', 'OTS');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('F2', 'Январь');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('G2', 'Февраль');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('H2', 'Март');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('I2', 'Апрель');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('J2', 'Май');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('K2', 'Июнь');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('L2', 'Июль');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('M2', 'Август');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('N2', 'Сентябрь');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('O2', 'Октябрь');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('P2', 'Ноябрь');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('Q2', 'Декабрь');

        $phpExcelObject->setActiveSheetIndex(0)->getStyle("A2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("B2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("C2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("D2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("E2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("F2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("G2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("H2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("I2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("J2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("K2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("L2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("M2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("N2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("O2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("P2")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("Q2")->getFont()->setBold(true);




        $orders = $banners;
        $line = 7;
        $sum = array(
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0,
            '10' => 0,
            '11' => 0,
            '12' => 0,
            '13' => 0,
            '14' => 0,
            '15' => 0,
            '16' => 0,
            '17' => 0,
        );
        foreach ($orders as $order){
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('A'.$line, $order->getArea());

            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('B'.$line, $order->getAdrs());

            $url = 'https://maps.yandex.ru/?text='.$order->getLatitude().','.$order->getLongitude();
            $phpExcelObject->setActiveSheetIndex(0)->getHyperlink('B'.$line)->setUrl($url);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('C'.$line, $order->getSide());
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('D'.$line, $order->getGrp());
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('E'.$line, $order->getOts());

            for ($i = 6; $i < 18 ; $i ++){
                $monthDate = new \DateTime('2015-'.($i-5).'-01 00:00:00');
                $sum[$i] += $order->getPrice();
                $phpExcelObject->setActiveSheetIndex(0)->setCellValue($this->getLetter($i).$line, $order->getPrice().'  р.');
                if ( $order->isMonth($monthDate) == 1 ){
                    $phpExcelObject->setActiveSheetIndex(0)->getStyle($this->getLetter($i).$line)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '00AA00')
                            )
                        )
                    );
                }elseif( $order->isMonth($monthDate) == 2 ){
                    $phpExcelObject->setActiveSheetIndex(0)->getStyle($this->getLetter($i).$line)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'AAAA00')
                            )
                        )
                    );
                }else{
                    $phpExcelObject->setActiveSheetIndex(0)->getStyle($this->getLetter($i).$line)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'AA0000')
                            )
                        )
                    );
                }
            }
            $line++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('List');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=export.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;

    }

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
     * @Route("/parserRus/{type}", name="parserRus")
     */
    public function parserRusAction($type){

        set_time_limit(0);
        ini_set("memory_limit","-1");

//        if ($type == 1){
//            $filename = '3x6/'.$filename.'.json';
//        }elseif($type == 2){
//            $filename = 'big/'.$filename;
//        }

        if ($type == 1){
            $folder = '3x6';
        }elseif($type == 2){
            $folder = 'big';
        }

        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Russoutdoor');
        if ($company == null){
            $company = new Company();
            $company->setTitle('Russoutdoor');
            $em->persist($company);
            $em->flush($company);
            $em->refresh($company);
        }

        $files = scandir($this->get('kernel')->getRootDir().'/../web/'.$folder);
        unset($files[1]);
        unset($files[0]);
        foreach($files as $f){
            $filename = $this->get('kernel')->getRootDir().'/../web/'.$folder.'/'.$f;
            $file = file_get_contents($filename);
            $array = json_decode($file,true);

            $i = 0;
            foreach ($array['billboards'] as $val){
                if (isset($val['longtitude']) && isset($val['latitude']) && isset($val['address'])){
                    $i ++ ;
                    $banner = new Banner();

                    $city = $this->getDoctrine()->getRepository('AppBundle:City')->findOneByTitle($val['cityName']);
                    if ($city == null){
                        $city = new City();
                        $city->setTitle($val['cityName']);
                        $em->persist($city);
                        $em->flush($city);
                        $em->refresh($city);
                    }

                    $banner->setCity($city);
                    $banner->setCompany($company);
                    $banner->setAdrs($val['address']);
                    $banner->setTitle($val['address']);
                    $banner->setGid($val['gid']);
                    $banner->setGrp($val['grp']);
                    $banner->setOts($val['ots']);
                    $banner->setPrice((isset($val['price']) ? $val['price'] : 0)*0.82);
                    $banner->setPrice2((isset($val['price']) ? $val['price'] : 0));
                    $banner->setLight($val['light']);
                    $banner->setSide($val['side']);
                    $desc = (isset($val['top']) ? $val['top'] : '').'<br />'.(isset($val['distance']) ? $val['distance'] : '' );
                    $banner->setBody($desc);
                    if ($type == 1){
                        $banner->setFormat('3x6');
                    }elseif($type == 2){
                        $banner->setFormat('big');
                    }
                    $banner->setLongitude($val['longtitude']);
                    $banner->setLatitude($val['latitude']);
                    $banner->setImg(str_replace('//','http://',$val['imageURL']));

                    $em->persist($banner);
                    $em->flush();

                }else{
                    echo $val['address'].'<br />';
                    echo '<br />';
                    print_r($val);
                    echo '<br />';
                    echo '<br />';
                }
            }
        }

        exit;

    }

}