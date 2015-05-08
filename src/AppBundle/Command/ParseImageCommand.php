<?php
namespace Evrika\MainBundle\Command;

use
    Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface,
    AppBundle\Entity\Banner;

class ParseImageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('vega:parse:image')
            ->setDescription('parser images for billbords');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('MSG : Обработка началась');


    }
}