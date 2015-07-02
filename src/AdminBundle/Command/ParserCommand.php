<?php
namespace AdminBundle\Command;

use AdminBundle\Parser\GellaryParser;
use AdminBundle\Parser\GemaParser;
use AdminBundle\Parser\RosveroParser;
use AdminBundle\Parser\VeraParser;
use AppBundle\Entity\Log;
use AppBundle\Entity\Sale;
use Proxies\__CG__\AppBundle\Entity\Company;
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
            $p = explode('_',$f);
            if ($p[0] === 'HOT'){
                $hot = $p[1];
            }else{
                $hot = false;
                if (is_numeric($p[0])){
                    $sale = $p[0];
                }else{
                    $sale = 0;
                }
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
//                    $output->write($sale);
//                    if ($sale != 0){
//                        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Вера Олимп');
//                        if ($company == null){
//                            $company = new Company();
//                            $company->setTitle('Вера Олимп');
//                            $em->persist($company);
//                            $em->flush($company);
//                            $em->refresh($company);
//                        }
//                        $city = $em->getRepository('AppBundle:City')->findOneById(1);
//                        for ($i = 1; $i <= 12 ; $i ++){
//                            $date = new \DateTime('2015-'.$i.'-01 00:00:00');
//                            $month = $em->getRepository('AppBundle:Sale')->findOneBy(array('date' => $date, 'company' => $company, 'city' => $city));
//                            if (!$month){
//
//                                $month = new Sale();
//                                $month->setCity($city);
//                                $month->setDate($date);
//                                $month->setCompany($company);
//                                $month->setPercent($sale);
//                                $em->persist($month);
//                                $em->flush($month);
//                            }
//                        }
//                    }
                }catch (\Exception $e){
                    echo $f.' Строка: '.$e->getLine().'. '.$e->getMessage();
                    if ($type != 0){
                        $log = new Log();
                        $log->setTitle('<span class="text-danger">'.$f.' Строка: '.$e->getLine().'. '.$e->getMessage().'</span>');
                        $em->persist($log);
                        $em->flush($log);
                    }
                }

            }
            if ($type == 3){
                $parser = new RosveroParser($em,$container,$path);
                try{
                    $parser->parserRasvero1Action($hot);
                    if ($sale != 0){
                        $company = $em->getRepository('AppBundle:Company')->findOneByTitle('Расверо');
                        if ($company == null){
                            $company = new Company();
                            $company->setTitle('Расверо');
                            $em->persist($company);
                            $em->flush($company);
                            $em->refresh($company);
                        }
                        $city = $em->getRepository('AppBundle:City')->findOneById(1);
                        for ($i = 1; $i <= 12 ; $i ++){
                            $date = new \DateTime('2015-'.$i.'-01 00:00:00');
                            $month = $em->getRepository('AppBundle:Sale')->findOneBy(array('date' => $date, 'company' => $company, 'city' => $city));
                            if (!$month){

                                $month = new Sale();
                                $month->setCity($city);
                                $month->setDate($date);
                                $month->setCompany($company);
                                $month->setPercent($sale);
                                $em->persist($month);
                                $em->flush($month);
                            }
                        }
                    }

                }catch (\Exception $e){
                    if ($type != 0){
                        $log = new Log();
                        $log->setTitle($f.' Строка: '.$e->getLine().'. '.$e->getFile().'. '.$e->getMessage());
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