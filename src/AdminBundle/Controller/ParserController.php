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
use Symfony\Component\HttpFoundation\Session\Session;
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
                    $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Гема');
                    if ($company) {
                        $em = $this->getDoctrine()->getManager();
                        $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = ' . $company->getId())->execute();
                    }
                    $parser = new GemaParser($em,$container,$path);
                    $parser->parserGema1Action($hot);
                }
                if ($type == 3){
                    $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Расверо');
                    if ($company) {
                        $em = $this->getDoctrine()->getManager();
                        $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = ' . $company->getId())->execute();
                    }
                    $parser = new RosveroParser($em,$container,$path);
                    $parser->parserRasvero1Action($hot);
                }
                if ($type == 2){
                    $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
                    if ($company) {
                        $em = $this->getDoctrine()->getManager();
                        $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = ' . $company->getId())->execute();
                    }
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
            $session = new Session();
            $session->getFlashBag()->set('success', 'Загрузка завершена');
        }
        return array();
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/files", name="parser-files")
     * @Template()
     */
    public function fileAction(Request $request){
        if ($request->getMethod() == 'POST'){
            $file = $request->files->get('file');
            $name = '';
            if ($request->request->get('hot') == 'on'){
                $name = 'HOT_';
            }
            if ($request->request->get('sale')){
                $name .= $request->request->get('sale').'_';
            }
            $type = $request->request->get('type');
            switch ($type){
                case 1 : $name .= 'Gema'; break;
                case 2 : $name .= 'VeraOlimp'; break;
                case 3 : $name .= 'Rosvero'; break;
                case 4 : $name .= 'Gallery_3x6'; break;
                case 5 : $name .= 'Gallery_scroll'; break;
                case 6 : $name .= 'Gallery_roller'; break;
            }

            if (is_file('../web/upload/files/'.$name)){
                unlink('../web/upload/files/'.$name);
            }
            move_uploaded_file($file->getPathName(),'../web/upload/files/'.$name);
            $session = new Session();
            $session->getFlashBag()->add('success','Файл добавлен');
        }
        $files = scandir('../web/upload/files');
        $logs = $this->getDoctrine()->getRepository('AppBundle:Log')->findBy(array('enabled' => true), array('id' => 'DESC'));
        return array('files' => $files, 'logs' => $logs);
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/files/remove/{filename}", name="parser-files-remove")
     * @Template()
     */
    public function fileremoveAction(Request $request,$filename){

        unlink('../web/upload/files/'.$filename);
        return $this->redirect($this->generateUrl('parser-files'));
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/files/sync", name="parser-files-sync")
     */
    public function runSyncAction(){
        $container = $this->container;
        exec("/bin/ps -axw | awk '{print $1\" \"$5\" \"$6}'", $out);
        foreach ( $out as $val){
            if (preg_match('/file:parser/', $val)) {
                $val = explode(' ',$val)[0];
                exec("/bin/kill -9 $val");
            }
        }
        $cmd = 'php ' . $container->get('kernel')->getRootDir() . '/console file:parser >> /dev/null &';
        exec($cmd);
        return $this->redirect($this->generateUrl('parser-files'));
    }

}