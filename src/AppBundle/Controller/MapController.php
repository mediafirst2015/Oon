<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Banner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class MapController extends Controller
{
    /**
     * @Route("/get-more-info" , name="get_more_info", options={ "expose" = true })
     */
    public function getMoreInfoAction(Request $request){
        $id = $request->request->get('id');
        if ($id != null){
            $object = $this->getDoctrine()->getRepository('AppBundle:Banner')->find($id);
        }else{
            $object = null;
        }
        return $this->render("AppBundle:Default:getInfo.html.twig", array('object' => $object));
    }

    /**
     * @Route("/get-objects" , name="get_objects", options={"expose"=true})
     */
    public function getObjectsAction(Request $request){

        $area = $request->request->get('area');
        $street = $request->request->get('street');
        if ($area == 'null'){
            $area = null;
        }
        $format = $request->request->get('format');
        $type = $request->request->get('type');
        $light = $request->request->get('light');
        if ($light == 'on'){
            $light = 1;
        }else{
            $light = null;
        }
        $grpMin = $request->request->get('grpMin');
        $grpMax = $request->request->get('grpMax');

        $otsMin = $request->request->get('otsMin');
        $otsMax = $request->request->get('otsMax');

        $priceMin = $request->request->get('priceMin');
        $priceMax = $request->request->get('priceMax');

        $id = $request->request->get('id');


        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->filter($id,$street,$area,$format,$type,$light,$grpMin,$grpMax,$otsMin,$otsMax,$priceMin,$priceMax);
        $objects = array();
        if ($banners != null){
            foreach ($banners as $banner){
                $adrs = $banner->getAdrs();
                $img = $banner->getImg();
                $img = str_replace(' ','%20', $img);
                $id = $banner->getId();
                $date = new \DateTime();
//                $price = $banner->getMonth($date);
//                $price = $price->getPrice();
                $price = $banner->getPrice();
                $side = $banner->getSide();
                $format = $banner->getFormat();
                $type = $banner->getType();
                $light = ($banner->getLight() == 0 ? 'Нет' : 'Да');
                $grp = $banner->getGrp();
                $ots = $banner->getOts();

                $objects[] = array(
                    'coords' => [ $banner->getLatitude(), $banner->getLongitude()],
                    'alt' => $banner->getAdrs(),
                    'content' =>
                        "
                        <input type='hidden' class='dataId' value='$id'>
                        <div class='map-title'>
	                    Адрес
                        $adrs
                        </div>
                        <div style='text-align: center; margin-bottom: 5px'>
                            <img src='$img' class='thrumb'/>
                        </div>
                        <table class='map-setting' style='margin: 0 auto'>
                            <tr>
                                <td>Стоимость</td>
                                <td>$price р.</td>
                            </tr>
                            <tr>
                                <td>Сторона</td>
                                <td>$side</td>
                            </tr>
                            <tr>
                                <td>Формат</td>
                                <td>$format</td>
                            </tr>
                            <tr>
                                <td>Тип</td>
                                <td>$type</td>
                            </tr>
                            <tr>
                                <td>Свет</td>
                                <td>$light</td>
                            </tr>
                            <tr>
                                <td>GRP</td>
                                <td>$grp</td>
                            </tr>
                            <tr>
                                <td>OTS</td>
                                <td>$ots</td>
                            </tr>
                        </table>
                        <br />
                        <table style='margin: 0 auto'>
                        <tr>
                            <td class='map-month' >янв</td>
                            <td class='map-month' >фев</td>
                            <td class='map-month' >мар</td>
                            <td class='map-month' >апр</td>
                            <td class='map-month' >май</td>
                            <td class='map-month' >июн</td>
                            <td class='map-month' >июл</td>
                            <td class='map-month' >авг</td>
                            <td class='map-month' >сен</td>
                            <td class='map-month' >окт</td>
                            <td class='map-month' >ноя</td>
                            <td class='map-month' >дек</td>
                        </tr>
                        <tr>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                            <td><img src='/bundles/app/images/month-act.png' /></td>
                        </tr>
                        </table>
                        <br />
                        <div style='text-align: right'>
                        <span class='btn addbasket' style='font-size: 15px'  data-id=\"$id\">Добавить в заказ</span>
                        </div>
                                                "
                );
            }
        }
        $objects = array('data' => $objects);

        return new JsonResponse($objects);
    }



    /**
     * @Route("get-coords", name="get_coords", options={"expose"=true})
     *
     */
    public function getCoordsAction(Request $request){
        $val = 'Москва '.$request->request->get('street');
//         preg_match("/[\d]+/", $val,$match);
        $url = 'http://geocode-maps.yandex.ru/1.x/?geocode=V'.urlencode($val);
        $content = file_get_contents($url);
        $XmlObj = simplexml_load_string($content);
        $coords = array('data' => array(
            explode(' ',$XmlObj->GeoObjectCollection->featureMember->GeoObject->Point->pos)[1],
            explode(' ',$XmlObj->GeoObjectCollection->featureMember->GeoObject->Point->pos)[0],
        ));
        return new JsonResponse($coords);
    }
}