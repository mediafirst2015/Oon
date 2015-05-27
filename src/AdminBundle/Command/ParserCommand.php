<?php
namespace AdminBundle\Command;

use AdminBundle\Parser\GellaryParser;
use AppBundle\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
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
                $parser = new GemaParser($em,$container,$path);
                try{
                    $parser->parserGema1Action($hot);
                }catch (\Exception $e){
                    if ($type != 0){
                        $log = new Log();
                        $log->setTitle($f.' Строка: '.$e->getLine().'. '.$e->getMessage());
                        $em->persist($log);
                        $em->flush($log);
                    }
                }

            }
            if ($type == 2){
                $parser = new VeraParser($em,$container,$path);
                try{
                    $parser->parserVera1Action($hot);
                    $parser->parserVera2Action($hot);
                    $parser->parserVera3Action($hot);
                    $parser->parserVera4Action($hot);
                    $parser->parserVera5Action($hot);
                    $parser->parserVera6Action($hot);
                    $parser->parseImageAction();
                }catch (\Exception $e){
                    if ($type != 0){
                        $log = new Log();
                        $log->setTitle($f.' Строка: '.$e->getLine().'. '.$e->getMessage());
                        $em->persist($log);
                        $em->flush($log);
                    }
                }

            }
            if ($type == 3){
                $parser = new RosveroParser($em,$container,$path);
                try{
                    $parser->parserRasvero1Action($hot);
                }catch (\Exception $e){
                    if ($type != 0){
                        $log = new Log();
                        $log->setTitle($f.' Строка: '.$e->getLine().'. '.$e->getMessage());
                        $em->persist($log);
                        $em->flush($log);
                    }
                }
            }
            if ($type == 4){
                $parser = new GellaryParser($em,$container,$path);
                try{
                    $parser->parserGellary1Action($hot);
                }catch (\Exception $e){
                    if ($type != 0){
                        $log = new Log();
                        $log->setTitle($f.' Строка: '.$e->getLine().'. '.$e->getMessage());
                        $em->persist($log);
                        $em->flush($log);
                    }
                }

            }
            if ($type == 5){
                $parser = new GellaryParser($em,$container,$path);
                try{
                    $parser->parserGellary2Action($hot);
                }catch (\Exception $e){
                    if ($type != 0){
                        $log = new Log();
                        $log->setTitle($f.' Строка: '.$e->getLine().'. '.$e->getMessage());
                        $em->persist($log);
                        $em->flush($log);
                    }
                }
            }
            if ($type == 6){
                $parser = new GellaryParser($em,$container,$path);
                try{
                    $parser->parserGellary3Action($hot);
                }catch (\Exception $e){
                    if ($type != 0){
                        $log = new Log();
                        $log->setTitle($f.' Строка: '.$e->getLine().'. '.$e->getMessage());
                        $em->persist($log);
                        $em->flush($log);
                    }
                }
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