<?php

declare(strict_types=1);

use Wenhsun\Leave\Domain\Service\EmployeeAnnualLeaveCalculator;
use Wenhsun\Leave\Infra\MySQLEmployeeLeaveRepository;
use Wenhsun\Leave\Infra\MySQLEmployeeRepository;

class AnnualLeaveCommand extends CConsoleCommand
{
    public function run($argv)
    {
        $nowDate = !empty($argv[0]) ? new DateTime($argv[0]) : new DateTime();

        $sut = new EmployeeAnnualLeaveCalculator(
            new MySQLEmployeeRepository(),
            new MySQLEmployeeLeaveRepository()
        );

        $sut->calculate($nowDate);

        echo "DONE\n";
    }
}