<?php

namespace App\Command;

use App\Repository\EmployeeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteCommand extends Command
{
    protected static $defaultName = 'DeleteCommand';
    protected static $defaultDescription = 'Supprimer un employé en fin de contrat';

    private $employeeRepository;
    private $entityManager;

    public function __construct(EmployeeRepository $employeeRepository, EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->employeeRepository = $employeeRepository;
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $employees = $this->employeeRepository->findall();

        foreach($employees as $employee){
            $endContract = $employee->getEndContract();
            $currentDay = new DateTime('now');

            if($endContract){
                if($endContract < $currentDay){
    
                    $this->entityManager->remove($employee);
                    $this->entityManager->flush();
    
                    $output->writeln('Employé suprimé');
                }              
            }
        }
        return Command::SUCCESS;
    }
}
