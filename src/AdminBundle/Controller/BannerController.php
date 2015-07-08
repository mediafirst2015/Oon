<?php
namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Banner;
use AppBundle\Form\BannerType;

/**
 * Class BannerController
 * @package AppBundle\Controller
 * @Route("/admin/banner")
 */
class BannerController extends Controller{
    const ENTITY_NAME = 'Banner';
    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/", name="banner_list")
     * @Template()
     */
    public function listAction(){
        $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findBy(array('enabled' => true),array('id' => 'DESC'));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('banner', 1),
            100
        );
        $pagination->setTemplate('AppBundle::default_pagination.html.twig');

        return array('pagination' => $pagination);
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/add", name="banner_add")
     * @Template()
     */
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $item = new Banner();
        $form = $this->createForm(new BannerType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('banner_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/edit/{id}", name="banner_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new BannerType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('banner_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/remove/{id}", name="banner_remove")
     */
    public function removeAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        if ($item){
            $em->remove($item);
            $em->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/admin-banner-month-list/{id}", name="admin_banner_month_list")
     * @Template()
     */
    public function monthBannerAction($id){
        $banner = $this->getDoctrine()->getRepository('AppBundle:Banner')->find($id);
        $months = $banner->getMonths();

        return array('banner' => $banner, 'month' => $months);
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/offer/list", name="banner_offer")
     * @Template()
     */
    public function offersAction(){
        $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->findBy(array('enabled' => true, 'offer' => true));
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $banners,
            $this->get('request')->query->get('banner', 1),
            100
        );
        return array('pagination' => $pagination);
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/offer-remove/{id}", name="offer_remove")
     */
    public function offerRemoveAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        if ($item){
            $item->setOffer(false);
            $em->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }



    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/offer-add/{id}", name="offer_add", defaults={"id" = null}, options={"expose"=true})
     * @Template()
     */
    public function offerAddAction(Request $request, $id = null){
        $em = $this->getDoctrine()->getManager();
        # Добавление баннера в предложение дня
        if ($id != null){
            $banner = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneById($id);
            if ($banner){
                $banner->setOffer(true);
                $em->flush();
                return new Response('add');
            }
        }
        #Вывод всех возможных баннеров
        else{
            $banners = $this->getDoctrine()->getRepository('AppBundle:Banner')->findBanners($request->query->get('search'));
            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $banners,
                $this->get('request')->query->get('page', 1),
                100
            );
            $pagination->setTemplate('AppBundle::default_pagination.html.twig');
            return array('pagination' => $pagination);
        }
    }


}