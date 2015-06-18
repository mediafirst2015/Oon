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
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {

//        $objects = $this->getDoctrine()->getRepository('AppBundle:Banner')->findAll();

        $reviews = $this->getDoctrine()->getRepository('AppBundle:Review')->findBy(array('enabled'=> true),array(),3);
        $logos = $this->getDoctrine()->getRepository('AppBundle:Logo')->findBy(array('enabled'=> true),array(),11);
        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->findBy(array('enabled'=> true,'hot' => true),array(),3);
        $cities = $this->getDoctrine()->getRepository('AppBundle:City')->findBy(array('enabled'=> true));
        $offers = $this->getDoctrine()->getRepository('AppBundle:Banner')->findBy(array('enabled'=> true,'offer' => true));
        return array('banners'=> $banners, 'reviews' => $reviews, 'logos' => $logos, 'cities' => $cities, 'offers' => $offers);
    }


    /**
     * @Route("/contacts", name="contacts")
     * @Template()
     */
    public function contactsAction(){
        return array();
    }

    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction(){
        return array();
    }


    /**
     * @Route("/page/{url}", name="page")
     * @Template()
     */
    public function pageAction($url)
    {
        if ($url == 'about'){
            return $this->render('AppBundle:Default:about.html.twig');
        }elseif($url == 'contacts'){
            return $this->render('AppBundle:Default:contacts.html.twig');
        }else{
        $page = $this->getDoctrine()->getRepository('AppBundle:Page')->findOneByUrl($url);
        return array('page' => $page);
        }
//        }
    }

    /**
     * @Route("/search", name="search")
     * @Template()
     */
    public function searchAction(Request $request){
        $params = array(
            'grpMin' => $request->query->get('grp-min'),
            'grpMax' => $request->query->get('grp-max'),
            'otsMin' => $request->query->get('ots-min'),
            'otsMax' => $request->query->get('ots-max'),
            'priceMin' => $request->query->get('price-min'),
            'priceMax' => $request->query->get('price-max'),
            'city' => $request->query->get('city'),
            'area' => $request->query->get('area'),
            'formatS' => $request->query->get('formatS'),
            'formatM' => $request->query->get('formatM'),
            'formatL' => $request->query->get('formatL'),
            'formatSB' => $request->query->get('formatSB'),
            'light' => $request->query->get('light'),
            'dateStart' => $request->query->get('dateStart'),
            'dateEnd' => $request->query->get('dateEnd'),
            'sideA' => $request->query->get('sideA'),
            'sideB' => $request->query->get('sideB'),
            'gid' => $request->query->get('gid'),
        );

        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->filter(
            null,
            $params['city'],
            $params['area'],
            $params['formatS'],
            $params['formatM'],
            $params['formatL'],
            $params['formatSB'],
            null,
            $params['light'],
            $params['grpMin'],
            $params['grpMax'],
            $params['otsMin'],
            $params['otsMax'],
            $params['priceMin'],
            $params['priceMax'],
            $params['sideA'],
            $params['sideB'],
            $params['gid'],
            0
        );
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $banners,
            $this->get('request')->query->get('page', 1),
            100
        );
        $pagination->setTemplate('AppBundle::default_pagination.html.twig');


        $lists = array();
        $i = 0;
        $grp = 0;
        $ots = 0;
        $ots2 = 0;
        $sideA = 0;
        $side = '';
        $sideB = 0;
        $price = 0;
        $price2 = 0;
        $fullprice = 0;
        $session = $request->getSession();
        $basket = $session->get('lists');
        if ($basket){
            foreach ($basket as $key=>$val){
                $i ++;
//                $lists[] = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($key);
                $grp += $val['grp'];
                $ots += $val['ots'];
                if ($val['side'] == 'А' || $val['side'] == 'A'){
                    $sideA ++;
                }else{
                    $sideB ++;
                }
                $price += $val['price'];
                $price2 += $val['price2'];
            }
            $lists = $basket;
            $grp = $grp / $i;
            $ots2 = $ots;
            $ots = $ots / $i;
            $fullprice = $price2;
            $price = $price / $i;
            $price2 = $price2 / $i;
            $grp = number_format($grp,2,'.','');
            $ots = number_format($ots,2,'.','');
            $price = number_format($price,2,'.','');
            $price2 = number_format($price2,2,'.','');
            $sideA = number_format(100/$i*$sideA,0,'.','');
            $sideB = number_format(100/$i*$sideB,0,'.','');
            $side = $sideA.'/'.$sideB;
        }

        $cities = $this->getDoctrine()->getRepository('AppBundle:City')->findBy(array('enabled'=> true));

        return array(
            'params' => $params,
            'banners' => $pagination,
            'lists' => $lists,
            'grp' => $grp,
            'ots'=>$ots,
            'otsSum'=> $ots2,
            'price' => $price,
            'price2' => $price2,
            'count' => $i,
            'side'=> $side,
            'fullPrice' => $fullprice,
            'cities' => $cities
        );
    }

    /**
     * @Route("/map", name="map")
     * @Template()
     */
    public function mapAction(Request $request){
        $session = new Session();
        $refer = $request->headers->get('referer');
        if ($request->query->get('my') == '0' && $session->get('referer') != null){
            if ($session->get('referer') != $refer ){
                $refer = $session->get('referer');
                $session->set('referer',null);
                return $this->redirect($refer);

            }
        }

//        if ($request->query->get('my') != 1){
            $session->set('referer',$refer);
            $session->save();
//        }
        $params = array(
            'grpMin' => $request->query->get('grp-min'),
            'grpMax' => $request->query->get('grp-max'),
            'otsMin' => $request->query->get('ots-min'),
            'otsMax' => $request->query->get('ots-max'),
            'priceMin' => $request->query->get('price-min'),
            'priceMax' => $request->query->get('price-max'),
            'area' => $request->query->get('area'),
            'formatS' => $request->query->get('formatS'),
            'formatM' => $request->query->get('formatM'),
            'formatL' => $request->query->get('formatL'),
            'formatSB' => $request->query->get('formatSB'),
            'light' => $request->query->get('light'),
            'street' => $request->query->get('street'),
            'city' => $request->query->get('city'),
            'hot' => $request->query->get('hot'),

            'dateStart' => $request->query->get('dateStart'),
            'dateEnd' => $request->query->get('dateEnd'),
        );

        if ($params['formatS'] == null){
            $params['formatS'] = 1;
        }
        if ($params['formatM'] == null){
            $params['formatM'] = 1;
        }
        if ($params['formatL'] == null){
            $params['formatL'] = 1;
        }
        if ($params['formatSB'] == null){
            $params['formatSB'] = 1;
        }

        if ( $request->query->get('city')){
            $city = $request->query->get('city').', ';
        }else{
            $city = 'Москва,';
        }
        if ( $request->query->get('area')){
            $area = $request->query->get('area').', ';
        }else{
            $area = '';
        }
        if ( $request->query->get('my') == 1 ){
            $basket = $session->get('lists');
            if (isset($basket) && $basket != null  && isset(array_values($basket)[0])){
                $val = array_values($basket)[0]['city'];
            }else{
                $val = 'Москва';
            }
        }else{
            $val = $city.' '.$area.' '.$request->query->get('street');
        }

            $url = 'http://geocode-maps.yandex.ru/1.x/?geocode='.urlencode($val);
            $content = file_get_contents($url);
            $XmlObj = simplexml_load_string($content);
        if (isset($XmlObj->GeoObjectCollection->featureMember->GeoObject->Point->pos)){
            $pos['x'] = explode(' ',$XmlObj->GeoObjectCollection->featureMember->GeoObject->Point->pos)[1];
            $pos['y'] = explode(' ',$XmlObj->GeoObjectCollection->featureMember->GeoObject->Point->pos)[0];
        }else{
            $pos['x'] = null;
            $pos['y'] = null;
        }


        $id = $request->query->get('bannerId');
        if ($id){
            $thisBanner = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($id);
        }else{
            $thisBanner = null;
        }


        $lists = array();
        $i = 0;
        $grp = 0;
        $ots = 0;
        $ots2 = 0;
        $sideA = 0;
        $side = '';
        $sideB = 0;
        $price = 0;
        $price2 = 0;
        $fullprice = 0;
        $session = $request->getSession();
        $basket = $session->get('lists');
        if ($basket){
            foreach ($basket as $key=>$val){
                $i ++;
//                $lists[] = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($key);
                $grp += $val['grp'];
                $ots += $val['ots'];
                if ($val['side'] == 'А' || $val['side'] == 'A'){
                    $sideA ++;
                }else{
                    $sideB ++;
                }
                $price += $val['price'];
                $price2 += $val['price2'];
            }
            $lists = $basket;
            $grp = $grp / $i;
            $ots2 = $ots;
            $ots = $ots / $i;
            $fullprice = $price2;
            $price = $price / $i;
            $price2 = $price2 / $i;
            $grp = number_format($grp,2,'.','');
            $ots = number_format($ots,2,'.','');
            $price = number_format($price,2,'.','');
            $price2 = number_format($price2,2,'.','');
            $sideA = number_format(100/$i*$sideA,0,'.','');
            $sideB = number_format(100/$i*$sideB,0,'.','');
            $side = $sideA.'/'.$sideB;
        }
        $cities = $this->getDoctrine()->getRepository('AppBundle:City')->findBy(array('enabled'=> true));
        return array(
            'id'=> $id,
            'cities' => $cities,
            'params' => $params,
            'lists' => $lists,
            'grp' => $grp,
            'ots'=>$ots,
            'otsSum'=> $ots2,
            'price' => $price,
            'price2' => $price2,
            'count' => $i,
            'side'=> $side,
            'fullPrice' => $fullprice,
            'thisBanner' => $thisBanner,
            'pos' => $pos,
            'street'=> $request->query->get('street')
        );
    }

    /**
     * @Route("/hot", name="hot")
     * @Template()
     */
    public function hotAction(Request $request){
        $params = array(
            'city' => $request->query->get('city'),
            'area' => $request->query->get('area'),
//            'street' => $request->query->get('street'),
            'formatS' => $request->query->get('formatS'),
            'formatM' => $request->query->get('formatM'),
            'formatL' => $request->query->get('formatL'),
            'formatSB' => $request->query->get('formatSB'),
        );

        if ($params['formatS'] == null){
            $params['formatS'] = 1;
        }
        if ($params['formatM'] == null){
            $params['formatM'] = 1;
        }
        if ($params['formatL'] == null){
            $params['formatL'] = 1;
        }
        if ($params['formatSB'] == null){
            $params['formatSB'] = 1;
        }

        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->filterHot($params);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $banners,
            $this->get('request')->query->get('page', 1),
            4
        );

        $lists = array();
        $i = 0;
        $grp = 0;
        $ots = 0;
        $ots2 = 0;
        $sideA = 0;
        $side = '';
        $sideB = 0;
        $price = 0;
        $price2 = 0;
        $fullprice = 0;
        $session = $request->getSession();
        $basket = $session->get('lists');
        if ($basket){
            foreach ($basket as $key=>$val){
                $i ++;
//                $lists[] = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($key);
                $grp += $val['grp'];
                $ots += $val['ots'];
                if ($val['side'] == 'А' || $val['side'] == 'A'){
                    $sideA ++;
                }else{
                    $sideB ++;
                }
                $price += $val['price'];
                $price2 += $val['price2'];
            }
            $lists = $basket;
            $grp = $grp / $i;
            $ots2 = $ots;
            $ots = $ots / $i;
            $fullprice = $price2;
            $price = $price / $i;
            $price2 = $price2 / $i;
            $grp = number_format($grp,2,'.','');
            $ots = number_format($ots,2,'.','');
            $price = number_format($price,2,'.','');
            $price2 = number_format($price2,2,'.','');
            $sideA = number_format(100/$i*$sideA,0,'.','');
            $sideB = number_format(100/$i*$sideB,0,'.','');
            $side = $sideA.'/'.$sideB;
        }

        $cities = $this->getDoctrine()->getRepository('AppBundle:City')->findBy(array('enabled'=> true));
        return array(
            'params' => $params,
            'lists' => $lists,
            'grp' => $grp,
            'ots'=>$ots,
            'otsSum'=>$ots2,
            'price' => $price,
            'price2' => $price2,
            'count' => $i,
            'side'=> $side,
            'fullPrice' => $fullprice,
            'banners' => $pagination,
            'cities' => $cities

        );
    }

    /**
     * @Route("/hot-ajax", name="hot_ajax" , options={"expose" = true})
     * @Template()
     */
    public function hotAjaxAction(Request $request){
        $session = $request->getSession();
        $basket = $session->get('lists');

        $params = array(
            'city' => $request->query->get('city'),
            'area' => $request->query->get('area'),
            'formatS' => $request->query->get('formatS'),
            'formatM' => $request->query->get('formatM'),
            'formatL' => $request->query->get('formatL'),
            'formatSB' => $request->query->get('formatSB'),
        );

        if ($params['formatS'] == null){
            $params['formatS'] = 1;
        }
        if ($params['formatM'] == null){
            $params['formatM'] = 1;
        }
        if ($params['formatL'] == null){
            $params['formatL'] = 1;
        }
        if ($params['formatSB'] == null){
            $params['formatSB'] = 1;
        }

        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->filterHot($params);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $banners,
            $this->get('request')->query->get('page', 1),
            4
        );

        return array('banners' => $pagination, 'basket' => $basket);
    }


    /**
     * @Route("/mediaplan", name="mediaplan")
     * @Template()
     */
    public function mapMediaplanAction(Request $request){

        $banners = $request->query->get('banners');

//        $banners = explode(';',$banners);
//        $objects = array();
//        foreach ($banners as $b){
//           if ($b && $b != ''){
//               $banner = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($b);
//               $objects[] = array(
//                   'coords' => [ $banner->getLatitude(), $banner->getLongitude()],
//                   'alt' => $banner->getAdrs(),
//                   'id' => $banner->getId(),
//                   'hot' => ( $banner->getHotMonth() == false ? 0 : 1),
//                   'format' => $banner->getFormat(),
////                    'content' => $this->renderView('AppBundle:Map:getInfo.html.twig', $params),
//               );
//           }
//        }

        return array(
//            'objects' => $objects,
            'get' => $banners,
        );
    }

}
