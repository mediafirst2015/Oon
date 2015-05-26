<?php
namespace AdminBundle\Command;

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
//        $em    = $this->getContainer()->get('doctrine')->getManager();
//        $raw   = $em->createQuery('
//            SELECT d.avatarPath
//            FROM EvrikaMainBundle:Doctor d
//        ')->getResult();

        $output->writeln("+++ начало парсинга!");


    }
}