<?php
namespace AppBundle\Controller;


use AppBundle\Form\ProfileType;
use AppBundle\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * Class AuthController
 * @package AppBundle\Controller
 */
class AuthController extends Controller
{

    /**
     * @Route("/first-1", name="first-1")
     */
    public function firstAction(){
        $manager = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setUsername('oper');
        $user->setSalt(md5(time()));
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword('oper', $user->getSalt());
        $user->setPassword($password);

        $user->setRoles('ROLE_OPERATOR');
        $user->setLastName('oper');
        $user->setFirstName('oper');
        $user->setSurName('oper');
        $user->setPhone('+79161111111');
        $user->setCompany('sdsd');
//
        $manager->persist($user);
        $manager->flush($user);
    }

    /**
     * @Route("/admin/login", name="login")
     * @Route("/login", name="user_login")
     * @Template("AdminBundle:Default:index.html.twig")
     */
    public function loginAction( Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $session = new Session();
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);

            $session->getFlashBag()->add('success','Неправильный логин или пароль');
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);

            $session->getFlashBag()->add('success','Неправильный логин или пароль');
        }

//        $referer = $request->headers->get('referer');
//        return $this->redirect($referer);

        if ($error){
            return $this->redirect($this->generateUrl('homepage'));
        }else{
            return $this->redirect($this->generateUrl('homepage'));
        }

    }

    /**
     * @Route("/register", name="register")
     * @Template()
     */
    public function registerAction(Request $request){
        if ($request->getMethod() == 'POST'){
            $manager = $this->getDoctrine()->getManager();
            $user = new User();
            $user->setUsername($request->request->get('username'));
            $user->setSalt(md5(time()));
            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword($request->request->get('password'), $user->getSalt());
            $user->setPassword($password);

            $user->setRoles('ROLE_UNCONFIRMED');
            $user->setLastName($request->request->get('lastName'));
            $user->setFirstName($request->request->get('firstName'));
            $user->setSurName('');
            $user->setPhone($request->request->get('phone'));
            $user->setCompany($request->request->get('companyTitle'));

            $manager->persist($user);
            $manager->flush($user);

            $session = new Session();
            $session->getFlashBag()->add('success','Ваша заявка принята. Пожалуйста, ожидайте подтверждения регистрации на указанный электронный адрес');

            @$this->get('email.service')->send(
                array('tulupov.m@gmail.com','ryabova.t@mediafirst.ru','kravtsova.m@mediafirst.ru'),
                array('AppBundle:Email:registerNotify.html.twig'),
                'Сообщение от navigator mediaFirst'
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
        return array();
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/my-profile", name="my_profile")
     * @Template()
     */
    public function myprofileAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneById($this->getUser()->getId());
        $form = $this->createForm(new ProfileType($em), $user);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $user = $formData->getData();
                $em->flush($user);
                $em->refresh($user);
                return array('form' => $form->createView(), 'message' => 'Сохранено');
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/change-password", name="change_password")
     * @Template()
     */
    public function changePasswordAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($this->getUser()->getId());
        $form = $this->createFormBuilder();
        $form
            ->add('password', 'repeated', array('type' => 'password', 'invalid_message' => 'пароли не совпадают', 'first_options'  => array('label' => 'Пароль'), 'second_options' => array('label' => 'Повторите пароль'),))
            ->add('submit', 'submit', array('label' => 'Изменить'))
            ->getForm();
        $formData = $form->handleRequest($request);
        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $user->setSalt(md5(time()));
                $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                $password = $encoder->encodePassword($request->request->get('password'), $user->getSalt());
                $user->setPassword($password);
                $em->flush($user);
                $em->refresh($user);
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            }
        }
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/history", name="history")
     * @Template()
     */
    public function historyAction(){
        $order = $this->getDoctrine()->getRepository('AppBundle:Order')->orderList($this->getUser()->getId());
        return array('orders' => $order);
    }


}
