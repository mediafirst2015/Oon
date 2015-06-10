<?php
namespace AppBundle\Controller;


use AppBundle\Entity\Order;
use AppBundle\Entity\OrderMonth;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class BasketController extends Controller
{
    /**
     * @Route("/basket", name="basket")
     * @Template()
     */
    public function listsAction(Request $request){
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $lists = $session->get('lists');
        return array('lists' => $lists);
    }


    /**
     * @Route("/order-price", name="order_price", options={"expose" = true})
     */
    public function orderPriceAction(Request $request){
        $session = $request->getSession();
        $fullprice = 0;
        $basket = $session->get('lists');
        if ($basket){
            foreach ($basket as $key=>$val){
                $fullprice += $val['price2'];
            }
        }
        $fullprice = number_format($fullprice,1);
//        $fullprice = $fullprice += 0;
        return new Response($fullprice);
    }


    /**
     * @Route("/basket-add/{itemId}/{month}/{year}", name="basket_add", options={"expose" = true}, defaults={"year" = null})
     */
    public function addAction(Request $request, $itemId, $month, $year = null){
        $nowDate = new \DateTime();
        if ($year == null){
            $year = $nowDate->format('Y');
        }

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $lists = $session->get('lists');
        if (isset($lists[$itemId.'-'.$month.'-'.$year])){
            unset($lists[$itemId.'-'.$month.'-'.$year]);
            $status = 'remove';
        }else{
            $banner = $em->getRepository('AppBundle:Banner')->findOneById($itemId);
            if ($banner){


                if ($banner->getHotMonth($month)){

                    $percent = $banner->getHotMonth($month)->getSale();

                    $price = $banner->getPrice() * ((100 - $percent)/100);
                    $price2 = $banner->getPrice2() * ((100 - $percent)/100);
                }else{
                    $company = $banner->getCompany();
                    if (!isset($company->getMonthlySales()[$month.$year])){
                        $percent = 0;
                    }else{
                        $percent = $company->getMonthlySales()[$month.$year]->getPercent();
                    }
                    $price = $banner->getPrice() * ((100 - $percent)/100);
                    $price2 = $banner->getPrice2() * ((100 - $percent)/100);
                }


                $lists[$itemId.'-'.$month.'-'.$year] = array(
                    'id' => $banner->getId(),
                    'city' => $banner->getCity()->getTitle(),
                    'gid' => $banner->getGid(),
                    'hot' => $banner->getHot(),
                    'area' => $banner->getArea(),
                    'adrs' => $banner->getAdrs(),
                    'img'  => $banner->getImg(),
                    'price'=> $price,
                    'price2'=> $price2,
                    'priceDeploy'=> $banner->getPriceDeploy(),
                    'tax'=> $banner->getTax(),
                    'taxType'=> $banner->getTaxType(),
                    'light'=> $banner->getLight(),
                    'longitude'=>$banner->getLongitude(),
                    'latitude'=>$banner->getLatitude(),
                    'side'=>$banner->getSide(),
                    'grp'=>$banner->getGrp(),
                    'ots'=>$banner->getOts(),
                    'format'=>$banner->getFormat(),
                    'type'=>$banner->getType(),
                    'month'=>$month,
                    'monthStr'=>$this->getMonth($month),
                    'year'=>$year,
                    'months' => serialize(array()),
                );
            }
            $status = 'save';
        }
        $session->set('lists',$lists);

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
        $basket = $session->get('lists');
        if ($basket){
            foreach ($basket as $key=>$val){
                $i ++;
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
        $params = array(
            'lists' => $lists,
            'grp' => $grp,
            'otsSum'=> $ots2,
            'ots'=>$ots,
            'price' => $price,
            'price2' => $price2,
            'count' => $i,
            'side'=> $side,
            'fullPrice' => $fullprice,
        );
        return $this->render('AppBundle:Basket:table.html.twig',$params);


    }

    /**
     * @Route("/basket-set-month/{itemId}/{year}/{month}", name="basket_set_month", options={"expose" = true})
     * @Template()
     */
    public function setMonthAction(Request $request, $itemId, $year, $month){
        $session = $request->getSession();
        $lists = $session->get('lists');
        if (isset($lists[$itemId])){
            if (
                isset($lists[$itemId]) &&
                isset($lists[$itemId]['months'])
            ){
                $months = unserialize($lists[$itemId]['months']);
            }else{
                $months = array();
            }

            if (isset($months[$year.'-'.$month])){
                unset($months[$year.'-'.$month]);
                $status = 'remove';
            }else{
                $months[$year.'-'.$month] = true;
                $status = 'save';
            }
        }else{
            $status = 'error';
        }
//        var_dump($months);
        $lists[$itemId]['months'] = serialize($months);
        $session->set('lists',$lists);

        $ms = 0;
        foreach( $lists as $l){
            $ms += count(unserialize($l['months'])) * $l['price'];
        }

        return new JsonResponse(array('status' => $status, 'price' => $ms));

    }

    /**
     * @Route("/basket-random", name="basket_random")
     * @Template()
     */
    public function randomAction(Request $request){
        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->findAll();
        $em  = $this->getDoctrine()->getManager();
        foreach ( $banners as $b ){
//            $grp = (rand(0,300))/100;
//            $ots = rand(0,500);
//            $area = 'СВАО';
//            $b->setArea($area);
//            $b->setOts($ots);
//            $b->setGrp($grp);
//            $side = rand(0,1);
//            $side = ($side == 0 ? 'A' : 'B');
//            $b->setSide($side);
            $price = $b->getPrice();
            $price=str_replace(",",'.',$price);
            $price=preg_replace("/[^x\d|*\.]/","",$price);
            echo $price.'<br/>';
            $b->setPrice($price);
            $em->flush($b);
        }
    }

    /**
     * @Route("/basket-remove/{itemId}", name="basket_remove", options={"expose" = true})
     * @Template()
     */
    public function removeAction(Request $request, $itemId){
        $session = $request->getSession();
        $lists = $session->get('lists');
        if (isset($lists[$itemId])){
            unset($lists[$itemId]);
            $session->set('lists',$lists);
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
            $basket = $session->get('lists');
            if ($basket){
                foreach ($basket as $key=>$val){
                    $i ++;
                    $lists[] = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($key);
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
                $grp = number_format($grp,2,'.','');
                $ots = number_format($ots,2,'.','');
                $price2 = number_format($price2,2,'.','');
                $price = number_format($price,2,'.','');
                $sideA = number_format(100/$i*$sideA,0,'.','');
                $sideB = number_format(100/$i*$sideB,0,'.','');
                $side = $sideA.'/'.$sideB;
            }
            $params = array(
                'lists' => $lists,
                'grp' => $grp,
                'ots'=>$ots,
                'otsSum'=>$ots2,
                'price' => $price,
                'price2' => $price2,
                'count' => $i,
                'side'=> $side,
                'fullPrice' => $fullprice,
            );
        }else{
            $status = 'error';
        }
        if ($request->getMethod() == 'POST'){
            return $this->render('AppBundle:Basket:table.html.twig',$params);
        }else{
//            $referer = $request->headers->get('referer');
//            return $this->redirect($referer);
            return $this->redirect($this->generateUrl('map').'#basket');
        }

    }

    /**
     * @Route("/basket-count", name="basket_count", options={"expose" = true})
     * @Template()
     */
    public function countAction(Request $request){
        $session = $request->getSession();
        $lists = $session->get('lists');
        $count = count($lists);
        $status = 'ok';
        return new JsonResponse(array('status' => $status, 'count' => $count));
    }


    /**
     * @Route("/basket-clear", name="basket_clear", options={"expose" = true})
     */
    public function clearBasket(Request $request){
        $session = $request->getSession();
        $session->set('lists',null);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @Route("/basket-save", name="basket_save", options={"expose" = true})
     * @Template()
     */
    public function saveBasketAction(Request $request){
        $session = $request->getSession();
        $lists = $session->get('lists');
        $group = hash('sha256', time());
        $em = $this->getDoctrine()->getManager();
        foreach ($lists as $id => $list){
            $banner = $this->getDoctrine()->getRepository('AppBundle:banner')->findOneById($id);
            $order = new Order();
            $order->setBanner($banner);
            $order->setClient($this->getUser());
            $order->setPrice($banner->getPrice());
            $order->setOrderGroup($group);
            $order->setStatus(0);
            $order->setEnabled(true);
            $em->persist($order);
            $em->flush($order);
            $em->refresh($order);
            $months = unserialize($lists[$id]['months']);
            foreach ($months as $date => $val){
                if ( $val ){
                    $month = new OrderMonth();
                    $date = new \DateTime($date.'-01 00:00:00');
                    $month->setDate($date);
                    $month->setOrder($order);
                    $month->setEnabled(1);
                    $em->persist($month);
                    $em->flush($month);
                    $em->refresh($month);
                }
            }
        }

        $banners = $this->getDoctrine()->getRepository('AppBundle:Order')->findByOrderGroup($group);
        return array('banners' => $banners, 'group' => $group);

    }

    /**
     * @Route("/basket-export-excel/{orderId}", name="basket_export_excel", options={"expose" = true})
     */
    public function exportToExcelAction(Request $request,$orderId){

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        for($i = 1; $i<200; $i ++){
            $phpExcelObject->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(30);
        }
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(40);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(20);
        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setLastModifiedBy("ООН")
            ->setTitle("Заказ номер ".$orderId)
            ->setSubject("Заказ номер ".$orderId)
            ->setDescription("")
            ->setKeywords("")
            ->setCategory("");

        /**
         * Заказчик
         */
        $userOrder = $this->getDoctrine()->getRepository('AppBundle:Order')->findOneByOrderGroup($orderId);
        $user = $userOrder->getClient();

        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('A1', 'ФИО:');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('A2', 'Email:');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('A3', 'Телефон:');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('A4', 'Компания:');

        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('B1', $user->getLastName().' '.$user->getFirstName().' '.$user->getSurName());
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('B2', $user->getUsername());
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('B3', $user->getPhone());
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('B4', $user->getCompany());


        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('A6', 'Округ');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('B6', 'Адрес');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('C6', 'Сторона');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('D6', 'GRP');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('E6', 'OTS');
//        for()
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('F6', 'Январь');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('G6', 'Февраль');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('H6', 'Март');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('I6', 'Апрель');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('J6', 'Май');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('K6', 'Июнь');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('L6', 'Июль');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('M6', 'Август');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('N6', 'Сентябрь');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('O6', 'Октябрь');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('P6', 'Ноябрь');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('Q6', 'Декабрь');

        $phpExcelObject->setActiveSheetIndex(0)->getStyle("A6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("B6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("C6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("D6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("E6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("F6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("G6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("H6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("I6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("J6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("K6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("L6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("M6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("N6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("O6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("P6")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("Q6")->getFont()->setBold(true);




        $orders = $this->getDoctrine()->getRepository('AppBundle:Order')->findByOrderGroup($orderId);
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
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('A'.$line, $order->getBanner()->getArea());

            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('B'.$line, $order->getBanner()->getAdrs());

            $url = 'https://maps.yandex.ru/?text='.$order->getBanner()->getLatitude().','.$order->getBanner()->getLongitude();
            $phpExcelObject->setActiveSheetIndex(0)->getHyperlink('B'.$line)->setUrl($url);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('C'.$line, $order->getBanner()->getSide());
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('D'.$line, $order->getBanner()->getGrp());
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('E'.$line, $order->getBanner()->getOts());

            for ($i = 6; $i < 18 ; $i ++){
                $monthDate = new \DateTime('2015-'.($i-5).'-01 00:00:00');
                if ( $order->isMonth($monthDate) ){
                    $sum[$i] += $order->getPrice();
                    $phpExcelObject->setActiveSheetIndex(0)->setCellValue($this->getLetter($i).$line, $order->getPrice());
                    $phpExcelObject->setActiveSheetIndex(0)->getStyle($this->getLetter($i).$line)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '0000FF')
                            )
                        )
                    );
                }
            }
            $line++;
        }
        $allSum = 0;
        for ($i = 6; $i < 18 ; $i ++){
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue($this->getLetter($i).$line,$sum[$i]);
            $phpExcelObject->setActiveSheetIndex(0)->getStyle($this->getLetter($i).$line)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FF0000')
                    )
                )
            );
            $allSum += $sum[$i];
        }

        $line++;
        $line++;
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('P'.$line,'ИТОГО');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('Q'.$line,$allSum);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle('Q'.$line)->applyFromArray(
            array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FF0000')
                )
            )
        );

        $phpExcelObject->getActiveSheet()->setTitle('List');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=order-'.$orderId.'.xls');
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

    public function getMonth($month){
        switch ($month) {
            case 1:
                return 'Январь';
            case 2:
                return 'Февраль';
            case 3:
                return 'Март';
            case 4:
                return 'Апрель';
            case 5:
                return 'Май';
            case 6:
                return 'Июнь';
            case 7:
                return 'Июль';
            case 8:
                return 'Август';
            case 9:
                return 'Сентябрь';
            case 10:
                return 'Октябрь';
            case 11:
                return 'Ноябрь';
            case 12:
                return 'Декабрь';
        }
    }

    /**
     * @Route("/send/mediaplan", name="send_mediaplan")
     */
    public function sendMailtestAction(Request $request){
        $session = new Session();
        $email = $request->request->get('email');
        $name = $request->request->get('name');
        $file = $this->generateExcel();
        $basket = $session->get('lists');
        $banners = array();
        foreach ($basket as $b){
            $banners[$b['id']] = $b['id'];
        }
        $url = 'banners=';
        foreach($banners as $b){
            $url .= $b.';';
        }
        $this->get('email.service')->send(
            array('tulupov.m@gmail.com','ryabova.t@mediafirst.ru','kravtsova.m@mediafirst.ru', $email),
            array('AppBundle:Email:order.html.twig', array(
                'name'  => $name,
                'url' => $url
            )),
            'Медиаплан от MediaFirst !',
            $file
        );
        $session->getFlashBag()->set('success', '<span>Дорогой клиент,</span><br /><br />Спасибо за обращение к нам. Будем рады ответить на все ваши вопросы.');
        return $this->redirect($this->generateUrl('map').'#basket');
    }

    /**
     * @Route("/send/contact", name="send_contact")
     */
    public function sendContactAction(Request $request){
        $session = new Session();
        $email = $request->request->get('email');
        $name = $request->request->get('name');
        $phone = $request->request->get('phone');
        $this->get('email.service')->send(
            array('tulupov.m@gmail.com','ryabova.t@mediafirst.ru','kravtsova.m@mediafirst.ru'),
            array('AppBundle:Email:contact.html.twig', array(
                'name'  => $name,
                'email'  => $email,
                'phone'  => $phone,
            )),
            'Сообщение от navigator mediaFirst',
            null
        );
        $session->getFlashBag()->set('success', '<span>Дорогой клиент,</span><br /><br />Спасибо за обращение к нам. наш оператор свяжется с Вами в ближайшее время.');
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @Route("/send/getmedia", name="send_getmedia")
     */
    public function sendGetMediaAction(Request $request){
        $session = new Session();
        $params = array(
            'email' =>   $request->request->get('email'),
            'name' =>    $request->request->get('name'),
            'phone' =>   $request->request->get('phone'),
            'city' =>    $request->request->get('city'),
            'area' =>    $request->request->get('area'),
            'formatM' => ( $request->request->get('formatM')==1 ? 'да' : 'нет' ) ,
            'formatS' => ( $request->request->get('formatS')==1 ? 'да' : 'нет' ),
            'formatL' => ( $request->request->get('formatL')==1 ? 'да' : 'нет' ),
            'formatSB' =>( $request->request->get('formatSB')==1 ? 'да' : 'нет' ),
            'start' =>   $this->getMonth($request->request->get('dateStart')),
            'end' =>     $this->getMonth($request->request->get('dateEnd')),
            'count' =>   $request->request->get('count'),
            'priceAll' =>$request->request->get('priceAll'),
        );


        $this->get('email.service')->send(
            array('tulupov.m@gmail.com','ryabova.t@mediafirst.ru','kravtsova.m@mediafirst.ru'),
            array('AppBundle:Email:mediaplan.html.twig', $params),
            'Сообщение от navigator mediaFirst',
            null
        );
        $session->getFlashBag()->set('success', '<span>Дорогой клиент,</span><br /><br />Спасибо за обращение к нам. наш оператор свяжется с Вами в ближайшее время.');
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }



    /**
     * Некий контроллер для генерации Excel
     */

    public function generateExcel(){
        $session = new Session();
        $basket = $session->get('lists');

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        for($i = 1; $i<500; $i ++){
            $phpExcelObject->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(20);
        }


        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(5);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(15);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(8);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(40);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(14);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth(14);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('O')->setWidth(14);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('P')->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('Q')->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('R')->setWidth(10);
        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setLastModifiedBy("mediafirst")
            ->setTitle("Сформированный медиаплан")
            ->setSubject("Сформированный медиаплан")
            ->setDescription("")
            ->setKeywords("")
            ->setCategory("");

        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('B5', '№');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('C5', 'Город');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('D5', 'Округ');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('E5', 'Адрес');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('F5', 'Период');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('G5', 'Сторона');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('H5', 'Формат');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('I5', 'Свет');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('J5', 'GRP');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('K5', 'OTS');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('L5', 'Стоимость без учета налогов');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('M5', 'Налог');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('N5', 'Стоимость с учетом налогов');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('O5', 'Стоимость монтажа с налогом');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('P5', 'Фото');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('Q5', 'Карта');
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('R5', 'Налог');


        $phpExcelObject->setActiveSheetIndex(0)->getStyle("B5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("C5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("D5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("E5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("F5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("G5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("H5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("I5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("J5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("K5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("L5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("M5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("N5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("O5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("P5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("Q5")->getFont()->setBold(true);
        $phpExcelObject->setActiveSheetIndex(0)->getStyle("R5")->getFont()->setBold(true);

        $line = 6;
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
        $num = 0;
        foreach ($basket as $o){
            $num ++ ;
            $url = 'https://maps.yandex.ru/?text='.$o['latitude'].','.$o['longitude'];
            $o['grp'] =  ( $o['grp'] == 0 ? 'нд' : $o['grp'] );
            $o['ots'] =  ( $o['ots'] == 0 ? 'нд' : $o['ots'] );
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('B'.$line, $num);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('C'.$line, $o['city']);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('D'.$line, $o['area']);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('E'.$line, $o['adrs']);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('F'.$line, $this->getMonth($o['month']));
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('G'.$line, $o['side']);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('H'.$line, $o['format']);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('I'.$line, ($o['light'] == 1 ? 'Есть' : 'Нет'));
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('J'.$line, $o['grp']);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('K'.$line, $o['ots']);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('L'.$line, $o['price'].'р.');
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('M'.$line, $o['taxType']);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('N'.$line, $o['price2'].'р.');
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('O'.$line, ( $o['priceDeploy'] != '' ? $o['priceDeploy'] : '0' ) .'р.');
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('P'.$line, 'Карта');
            $phpExcelObject->setActiveSheetIndex(0)->getHyperlink('P'.$line)->setUrl($url);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('Q'.$line, 'Фото');
            $phpExcelObject->setActiveSheetIndex(0)->getHyperlink('Q'.$line)->setUrl($o['img']);
            $phpExcelObject->setActiveSheetIndex(0)->setCellValue('R'.$line, $o['taxType']);

            $line++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('List');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $time = time();
        $path = $this->container->getParameter('path_tmp').$time.'.xls';
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $writer->save($path);


        return $path;

    }


}