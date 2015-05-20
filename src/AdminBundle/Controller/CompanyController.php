<?php
namespace AdminBundle\Controller;

use AppBundle\Entity\Sale;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Company;
use AppBundle\Form\CompanyType;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Class CompanyController
 * @package AppBundle\Controller
 * @Route("/admin/company")
 */
class CompanyController extends Controller{
    const ENTITY_NAME = 'Company';
    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/", name="company_list")
     * @Template()
     */
    public function listAction(){
        $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('company', 1),
            500
        );

        return array('pagination' => $pagination);
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/add", name="company_add")
     * @Template()
     */
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $item = new Company();
        $form = $this->createForm(new CompanyType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('company_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/edit/{id}", name="company_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new CompanyType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('company_list'));
            }
        }
        return array('form' => $form->createView(), 'id' => $id);
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/remove/{id}", name="company_remove")
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
     * @Route("/sale-list/{companyId}", name="sale_list")
     * @Template()
     */
    public function salesAction(Request $request, $companyId){
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($companyId);
        $cities = $this->getDoctrine()->getRepository('AppBundle:City')->findAll();
        $sales = $item->getSales();

//        $paginator  = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//            $items,
//            $this->get('request')->query->get('company', 1),
//            500
//        );

        return array('cities' => $cities, 'sales' => $sales, 'id' => $companyId);
    }


    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/sale-add/{companyId}", name="sale_add")
     * @Template()
     */
    public function saleAddAction(Request $request, $companyId){
        if ($request->getMethod() == 'POST'){
            $city = $this->getDoctrine()->getRepository('AppBundle:City')->findOneById($request->request->get('city'));
            $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneById($companyId);
            $date = new \DateTime();
            $dateString = $date->format('Y').'-'.$request->request->get('month').'-01 00:00:00';
            $date = new \DateTime($dateString);

            $sale = new Sale();
            $sale->setCity($city);
            $sale->setDate($date);
            $sale->setCompany($company);
            $sale->setPercent($request->request->get('percent'));

            $this->getDoctrine()->getManager()->persist($sale);
            $this->getDoctrine()->getManager()->flush($sale);
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/sale-remove/{saleId}", name="sale_remove")
     * @Template()
     */
    public function saleRemoveAction(Request $request, $saleId){
        $sale = $this->getDoctrine()->getRepository('AppBundle:Sale')->findOneById($saleId);
        if ($sale){
            $this->getDoctrine()->getManager()->remove($sale);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }

}