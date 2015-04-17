<?php
namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("admin/banner/parser")
 */
class ParserController extends Controller{

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/", name="parser")
     * @Template()
     */
    public function indexAction(Request $request){
//        if ($request->getMethod() == 'POST'){
//            if ($request->files)
//        }
        return array();
    }
}