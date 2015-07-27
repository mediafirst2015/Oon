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

    protected $month = array(
        1 => 'Янв',
        2 => 'Фев',
        3 => 'Мар',
        4 => 'Апр',
        5 => 'Май',
        6 => 'Июн',
        7 => 'Июл',
        8 => 'Авг',
        9 => 'Сен',
        10 => 'Окт',
        11 => 'Ноя',
        12 => 'Дек',
    );

    /**
     * @Route("/get-more-info" , name="get_more_info", options={ "expose" = true })
     */
    public function getMoreInfoAction(Request $request){
        $session = $request->getSession();
        $myBasket = $session->get('lists');
        if ($request->request->get('itemId') != null){
            $id = $request->request->get('itemId');
            if ($id != null){
                $object = $this->getDoctrine()->getRepository('AppBundle:Banner')->find($id);
            }else{
                $object = null;
            }

            return $this->render("AppBundle:Map:getInfo.html.twig", array('object' => $object,'myBasket' => $myBasket,'month' => $this->month));
        }else{
            $items = $request->request->get('items');
            $items = explode('|',$items);
            $resourses = array();
            foreach($items as $id){
                if ($id && $id != '' && $id != null){
                    $object = $this->getDoctrine()->getRepository('AppBundle:Banner')->find($id);
                    $resourses[] = array(
                        'title' => 'Объект '.$id,
                        'body'  => $this->renderView("AppBundle:Map:getInfo.html.twig", array('object' => $object,'myBasket' => $myBasket,'month' => $this->month))
                    );
                }
            }

            return new JsonResponse(array('data' => $resourses));
        }
    }

    /**
     * @Route("/get-objects" , name="get_objects", options={"expose"=true})
     */
    public function getObjectsAction(Request $request){

        $session = $request->getSession();
        $myBasket = $session->get('lists');
        $city = $request->request->get('city');

        $area = $request->request->get('area');

        $street = $request->request->get('street');
        if ($area == 'null'){
            $area = null;
        }
        $formatS = $request->request->get('formatS');
        $formatM = $request->request->get('formatM');
        $formatL = $request->request->get('formatL');
        $formatSB = $request->request->get('formatSB');

        $type = $request->request->get('type');
        $light = $request->request->get('light');
        if ($light == 'on'){
            $light = 1;
        }
        $grpMin = $request->request->get('grpMin');
        $grpMax = $request->request->get('grpMax');

        $otsMin = $request->request->get('otsMin');
        $otsMax = $request->request->get('otsMax');

        $priceMin = $request->request->get('priceMin');
        $priceMax = $request->request->get('priceMax');

        $hot = $request->request->get('hot');
        if ($hot == 'on'){
            $hot = 1;
        }

        $id = $request->request->get('id');


        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->filter($id,$city,$area,$formatS,$formatM,$formatL,$formatSB,$type,$light,$grpMin,$grpMax,$otsMin,$otsMax,$priceMin,$priceMax,null,null,null,$hot);
        $objects = array();
        if ($banners != null){
            foreach ($banners as $banner){
                $city = $banner->getCity();
                $adrs   = $banner->getAdrs();
                $img    = $banner->getImg();
                $img    = str_replace(' ','%20', $img);
                $id     = $banner->getId();
                $price  = $banner->getPrice();
                $side   = $banner->getSide();
                $format = $banner->getFormat();
                $type   = $banner->getType();
                $light  = ($banner->getLight() == 0 ? 'Нет' : 'Да');
                $grp    = $banner->getGrp();
                $ots    = $banner->getOts();
                $months = $banner->getMonths();

                $params = array(
                    'city'   => $city,
                    'adrs'   => $adrs,
                    'img'    => $img,
                    'id'     => $id,
                    'price'  => $price,
                    'side'   => $side,
                    'format' => $format,
                    'type'   => $type,
                    'light'  => $light,
                    'grp'    => $grp,
                    'ots'    => $ots,
                    'months' => $months,
                    'myBasket' => $myBasket
                );
                $objects[] = array(
                    'coords' => [ $banner->getLatitude(), $banner->getLongitude()],
                    'alt' => $banner->getAdrs(),
                    'id' => $banner->getId(),
//                    'hot' => ( $banner->getHotMonth() == false ? 0 : 1),
                    'hot' => ( $banner->getHot() == false ? 0 : 1),
                    'format' => $banner->getFormat(),
                    'type' => $banner->getType(),
//                    'content' => $this->renderView('AppBundle:Map:getInfo.html.twig', $params),
                );
            }
        }
        $objects = array('data' => $objects);

        return new JsonResponse($objects);
    }

    /**
     * @Route("/get-my-objects", name="get_my_objects")
     */
    public function getMyObjectsAction(Request $request){
        $session = $request->getSession();
        $banners = $session->get('lists');
        $objects = array();
        if ($banners != null){
            foreach ($banners as $banner){
                $b = $banner;
                $banner = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($banner['id']);
                $city   = $banner->getCity();
                $adrs   = $banner->getAdrs();
                $img    = $banner->getImg();
                $img    = str_replace(' ','%20', $img);
                $id     = $banner->getId();
                $price  = $banner->getPrice();
                $side   = $banner->getSide();
                $format = $banner->getFormat();
                $type   = $banner->getType();
                $light  = ($banner->getLight() == 0 ? 'Нет' : 'Да');
                $grp    = $banner->getGrp();
                $ots    = $banner->getOts();
                $months = $banner->getMonths();

                $params = array(
                    'adrs'   => $adrs,
                    'city'   => $city,
                    'img'    => $img,
                    'id'     => $id,
                    'price'  => $price,
                    'side'   => $side,
                    'format' => $format,
                    'type'   => $type,
                    'light'  => $light,
                    'grp'    => $grp,
                    'ots'    => $ots,
                    'months' => $months
                );

                $objects[$id] = array(
                    'coords' => [ $banner->getLatitude(), $banner->getLongitude()],
                    'alt' => $banner->getAdrs(),
                    'id' => $banner->getId(),
                    'hot' => ( $banner->getHot() == false ? 0 : 1),
                    'format' => $banner->getFormat(),
//                    'content' => $this->renderView('AppBundle:Map:getInfo.html.twig', $params),
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


    /**
     * @Route("get-objects-mediaplan", name="get_objects_mediaplan", options={"expose"=true})
     */
    public function getObjectsMediaplanAction(Request $request){
        $banners = $request->query->get('banners');

        $banners = explode(';',$banners);
        $objects = array();
        foreach ($banners as $b){
            if ($b && $b != ''){
                $banner = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($b);
                if ($banner){
                    $objects[] = array(
                        'coords' => [ $banner->getLatitude(), $banner->getLongitude()],
                        'alt' => $banner->getAdrs(),
                        'id' => $banner->getId(),
                        'hot' => ( $banner->getHot() == false ? 0 : 1),
//                        'hot' => ( $banner->getHotMonth() == false ? 0 : 1),
                        'format' => $banner->getFormat(),
                        'type' => $banner->getType(),

//                    'content' => $this->renderView('AppBundle:Map:getInfo.html.twig', $params),
                    );
                }
            }
        }
        $objects = array('data' => $objects);

        return new JsonResponse($objects);
    }
}