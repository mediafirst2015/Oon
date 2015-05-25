<?php
namespace AdminBundle\Controller;

ini_set('memory_limit', '-1');

use AdminBundle\Parser\GellaryParser;
use AdminBundle\Parser\GemaParser;
use AdminBundle\Parser\RosveroParser;
use AdminBundle\Parser\VeraParser;
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
                    $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Gallery 3x6');
                    if ($company){
                        $em = $this->getDoctrine()->getManager();
                        $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = '.$company->getId())->execute();
                    }

                    $parser = new GellaryParser($em,$container,$path);
                    $parser->parserGellary1Action($hot);
                }
                if ($type == 5){
                    $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Gallery scroll');
                    if ($company) {
                        $em = $this->getDoctrine()->getManager();
                        $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = ' . $company->getId())->execute();
                    }
                    $parser = new GellaryParser($em,$container,$path);
                    $parser->parserGellary2Action($hot);
                }
                if ($type == 6){
                    $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Gallery  roller');
                    if ($company) {
                        $em = $this->getDoctrine()->getManager();
                        $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = ' . $company->getId())->execute();
                    }
                    $parser = new GellaryParser($em,$container,$path);
                    $parser->parserGellary3Action($hot);
                }

                if ($type == 1){
                    $parser = new GemaParser($em,$container,$path);
                    $parser->parserGema1Action($hot);
                }
                if ($type == 3){
                    $parser = new RosveroParser($em,$container,$path);
                    $parser->parserRasvero1Action($hot);
                }
                if ($type == 2){
                    $parser = new VeraParser($em,$container,$path);
                    $parser->parserVera1Action($hot);
                    $parser->parserVera2Action($hot);
                    $parser->parserVera3Action($hot);
                    $parser->parserVera4Action($hot);
                    $parser->parserVera5Action($hot);
                    $parser->parserVera6Action($hot);
                    $parser->parseImageAction();

                }



            }
        }
        return array();
    }
}