<?php

namespace App\Command;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:populate',
    description: 'Add a short description for your command',
)]
class PopulateCommand extends Command
{
    public function __construct(protected CompanyRepository $companyRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $company = new Company();
        $company->setName("EPIXELIC");
        $company->setUrl("https://epixelic.com");
        $this->companyRepository->add($company, true);
        $company = new Company();
        $company->setName("Google");
        $company->setUrl("https://google.com");
        $this->companyRepository->add($company, true);

        return Command::SUCCESS;
    }
}
