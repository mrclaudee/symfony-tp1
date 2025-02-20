<?php

namespace App\Command;

use App\Entity\Deal;
use App\Repository\DealRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    'deal:price:increase',
)]
class DealPriceIncreaseCommand extends Command
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }

    protected function configure()
    {
        $this->setDescription('Ajoute les prix sur un ou tous les articles')
            ->addArgument('amount', InputArgument::REQUIRED, 'Quel est le montant ?')
            ->setHelp('Cette commande permet d\'ajouter les prix sur un ou tous les articles')
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'Quel est id du deal')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Tous les deals seront modifiés');
    }

    public function initialize(InputInterface $input, OutputInterface $output): void
    {
        $id = $input->getOption('id');
        $all = $input->getOption('all');
        if ($id && $all) {
            throw new LogicException("Vous ne pouvez fournir qu'une seule option.");
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $amount = (float) $input->getArgument('amount');
        $id = $input->getOption('id');
        $all = $input->getOption('all');

        $em = $this->doctrine->getManager();

        if ($all) {
            $deals = $this->doctrine->getRepository(Deal::class)->findAll();
            $progress = new ProgressBar($output, count($deals));
            $table = new Table($output);
            $table->setHeaders(['Id', 'Ancien prix', 'Nouveau prix']);
            $progress->start();
            foreach ($deals as $deal) {
                $deal->setPrice($deal->getPrice() + $amount);
                $em->persist($deal);
                $em->flush();
                //sleep(1);
                $progress->advance();
            }
            $progress->finish();

            $rows = [];
            foreach ($deals as $deal) {
                $row = [$deal->getId(), $deal->getPrice() - $amount, $deal->getPrice()];
                $rows[] = $row;
            }
            $table->setRows($rows);
            $output->writeln(['', '', 'Récapitulatif']);
            $table->render();
        }

        if ($id) {
            $deal = $this->doctrine->getManager()->getRepository(Deal::class)->find($id);
            if (!$deal) {
                $output->writeln('<error>Aucun deal avec cet id.</error>');
                return Command::FAILURE;
            }
            $deal->setPrice($deal->getPrice() + $amount);
            $em->persist($deal);
            $em->flush();
            $output->writeln('<info>Hausse du prix de l\'id '. $deal->getId(). ' de '. $deal->getPrice() - $amount .' à '. $deal->getPrice() .'.</info>');
        }

        return Command::SUCCESS;

    }
}