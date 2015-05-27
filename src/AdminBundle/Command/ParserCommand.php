<?php
namespace AdminBundle\Command;

use AdminBundle\Parser\GellaryParser;
use AppBundle\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AdminBundle\Parser\MainParser;

class ParserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('file:parser');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);
        ini_set("memory_limit","-1");

        $container = $this->getContainer();
        $em    = $this->getContainer()->get('doctrine')->getManager();
        $em->createQuery('DELETE FROM AppBundle:Log l')->execute(); // Удаляем старые логи

        $log = new Log();
        $log->setTitle('Начало синхронизации');
        $em->persist($log);
        $em->flush($log);

        $files = scandir($container->get('kernel')->getRootDir().'/../web/upload/files');

        foreach($files as $f){


            /**
             * Проверяем этот файл горящих предложений или нет
             */
            $pos = strripos($f, 'HOT');
            if ($pos === false){
                $hot = false;
            }else{
                $hot = true;
            }

            $path = $container->get('kernel')->getRootDir().'/../web/upload/files/'.$f;

            $type = 0;
            if ( strripos($f,'Gema') !== false ){ $type = 1; }
            if ( strripos($f,'VeraOlimp') !== false ){ $type = 2; }
            if ( strripos($f,'Rosvero') !== false ){ $type = 3; }
            if ( strripos($f,'Gallery_3x6') !== false ){ $type = 4; }
            if ( strripos($f,'Gallery_scroll') !== false ){ $type = 5; }
            if ( strripos($f,'Gallery_roller') !== false ){ $type = 6; }

            if ($type != 0){
                $log = new Log();
                $log->setTitle('Синхронизация '.$f);
                $em->persist($log);
                $em->flush($log);
            }

            if ($type == 1){
                $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Гема');
//                if ($company) {
//                    $em = $this->getDoctrine()->getManager();
//                    $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = ' . $company->getId())->execute();
//                }
                $parser = new GemaParser($em,$container,$path);
                $parser->parserGema1Action($hot);
            }
            if ($type == 2){
                $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
//                if ($company) {
//                    $em = $this->getDoctrine()->getManager();
//                    $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = ' . $company->getId())->execute();
//                }
                $parser = new VeraParser($em,$container,$path);
                $parser->parserVera1Action($hot);
                $parser->parserVera2Action($hot);
                $parser->parserVera3Action($hot);
                $parser->parserVera4Action($hot);
                $parser->parserVera5Action($hot);
                $parser->parserVera6Action($hot);
                $parser->parseImageAction();

            }
            if ($type == 3){
                $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Расверо');
//                if ($company) {
//                    $em = $this->getDoctrine()->getManager();
//                    $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = ' . $company->getId())->execute();
//                }
                $parser = new RosveroParser($em,$container,$path);
                $parser->parserRasvero1Action($hot);
            }
            if ($type == 4){
//                $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Gallery 3x6');
//                if ($company){
//                    $em = $this->getDoctrine()->getManager();
//                    $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = '.$company->getId())->execute();
//                }
                $parser = new GellaryParser($em,$container,$path);
                $parser->parserGellary1Action($hot);
            }
            if ($type == 5){
//                $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Gallery scroll');
//                if ($company) {
//                    $em = $this->getDoctrine()->getManager();
//                    $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = ' . $company->getId())->execute();
//                }
                $parser = new GellaryParser($em,$container,$path);
                $parser->parserGellary2Action($hot);
            }
            if ($type == 6){
//                $company = $this->getDoctrine()->getRepository('AppBundle:Company')->findOneByTitle('Gallery  roller');
//                if ($company) {
//                    $em = $this->getDoctrine()->getManager();
//                    $em->createQuery('DELETE FROM AppBundle:Banner b WHERE b.company = ' . $company->getId())->execute();
//                }
                $parser = new GellaryParser($em,$container,$path);
                $parser->parserGellary3Action($hot);
            }

            if ($type != 0){
                $log = new Log();
                $log->setTitle('Синхронизация '.$f.' завершена');
                $em->persist($log);
                $em->flush($log);
            }

        }



    }
}