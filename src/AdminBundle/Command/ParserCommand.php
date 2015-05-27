<?php
namespace AdminBundle\Command;

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
        $em    = $this->getContainer()->get('doctrine')->getManager();
        $raw   = $em->createQuery('
            DELETE FROM AppBundle:log l
        ')->execute();

        $log = new Log();
        $log->setTitle('Начало парсинга');

        $output->writeln("+++ начало парсинга!");


    }
}