<?php
namespace AdminBundle\Controller;

use AdminBundle\Parser\GellaryParser;
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
        if ($request->getMethod() == 'POST'){
            $file = $request->files->get('xml');
            if ($file){
                $type = $request->request->get('type');
                $percent = $request->request->get('percent');
                $hot = $request->request->get('hot');
                if ($hot == 'on'){
                    $hot = true;
                }else{
                    $hot = false;
                }
                $em = $this->getDoctrine()->getManager();
                $path = $file->getPathName();
                $container = $this->container;
                if ($type == 4){
                    $parser = new GellaryParser($em,$container,$path);
                    $parser->parserGellary1Action($hot);
                }
                if ($type == 5){
                    $parser = new GellaryParser($em,$container,$path);
                    $parser->parserGellary2Action($hot);
                }


            }
        }
        return array();
    }
}