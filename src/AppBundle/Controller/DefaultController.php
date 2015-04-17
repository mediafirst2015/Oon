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

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {

//        $objects = $this->getDoctrine()->getRepository('AppBundle:Banner')->findAll();


        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->findBy(array('enabled'=> true,'hot' => true),array(),3);
        return array('banners'=> $banners);
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
//        if ($url == 'about'){
//            return $this->render('AppBundle:Default:about.html.twig');
//        }else{
        $page = $this->getDoctrine()->getRepository('AppBundle:Page')->findOneByUrl($url);
        return array('page' => $page);
//        }
    }

    /**
     * @Route("/search", name="search")
     * @Template()
     */
    public function searchAction(Request $request){
        return array();
    }

    /**
     * @Route("/map", name="map")
     * @Template()
     */
    public function mapAction(Request $request){
        $params = array(
            'grpMin' => $request->query->get('grp-min'),
            'grpMax' => $request->query->get('grp-max'),
            'otsMin' => $request->query->get('ots-min'),
            'otsMax' => $request->query->get('ots-max'),
            'priceMin' => $request->query->get('price-min'),
            'priceMax' => $request->query->get('price-max'),
            'area' => $request->query->get('area'),
            'format' => $request->query->get('format'),
            'light' => $request->query->get('light'),
            'street' => $request->query->get('street'),
        );

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
        $val = $city.' '.$area.' '.$request->query->get('street');
        if ($val != ' ' && $val != '  '){
            $url = 'http://geocode-maps.yandex.ru/1.x/?geocode='.urlencode($val);
            $content = file_get_contents($url);
            $XmlObj = simplexml_load_string($content);
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
        $sideA = 0;
        $side = '';
        $sideB = 0;
        $price = 0;
        $fullprice = 0;
        $session = $request->getSession();
        $basket = $session->get('lists');
        if ($basket){
            foreach ($basket as $key=>$val){
                $i ++;
                $lists[] = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($key);
                $grp += $val['grp'];
                $ots += $val['ots'];
                if ($val['side'] == 'A'){
                    $sideA ++;
                }else{
                    $sideB ++;
                }
                $price += $val['price'];
            }
            $grp = $grp / $i;
            $ots = $ots / $i;
            $fullprice = $price;
            $price = $price / $i;
            $grp = number_format($grp,2,'.','');
            $ots = number_format($ots,2,'.','');
            $price = number_format($price,2,'.','');
            $sideA = number_format(100/$i*$sideA,0,'.','');
            $sideB = number_format(100/$i*$sideB,0,'.','');
            $side = $sideA.'/'.$sideB;
        }
        return array(
            'id'=> $id,
            'params' => $params,
            'lists' => $lists,
            'grp' => $grp,
            'ots'=>$ots,
            'price' => $price,
            'count' => $i,
            'side'=> $side,
            'fullPrice' => $fullprice,
            'thisBanner' => $thisBanner,
            'pos' => $pos,
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
            'street' => $request->query->get('street'),
            'packet' => $request->query->get('packet'),
            'type' => $request->query->get('type'),
        );
        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->filterHot($params);
        return array('banners' => $banners);
    }

}
