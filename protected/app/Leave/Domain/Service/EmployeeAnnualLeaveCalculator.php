<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Service;

use DateTime;
use RuntimeException;
use Wenhsun\Leave\Domain\Model\AnnualLeave;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeLeave;
use Wenhsun\Leave\Domain\Model\EmployeeLeaveRepository;
use Wenhsun\Leave\Domain\Model\EmployeeRepository;
use Wenhsun\Leave\Domain\Model\LeaveRecord;
use Wenhsun\Leave\Domain\Model\Minute;

class EmployeeAnnualLeaveCalculator
{
    private $employeeRepository;
    private $employeeLeaveRepository;

    private $leaveMap = [
        1 => 7,
        2 => 10,
        3 => 14,
        4 => 14,
        5 => 15,
        6 => 15,
        7 => 15,
        8 => 15,
        9 => 15,
        10 => 16,
        11 => 17,
        12 => 18,
        13 => 19,
        14 => 20,
        15 => 21,
        16 => 22,
        17 => 23,
        18 => 24,
        19 => 25,
        20 => 26,
        21 => 27,
        22 => 28,
        23 => 29,
        24 => 30,
        25 => 30,
    ];

    public function __construct(
        EmployeeRepository $employeeRepository,
        EmployeeLeaveRepository $employeeLeaveRepository
    ) {
        $this->employeeRepository = $employeeRepository;
        $this->employeeLeaveRepository = $employeeLeaveRepository;
    }

    public function calculate(DateTime $nowDate): void
    {
        $nowDate->setTime(0, 0);
        $employees = $this->employeeRepository->getEmployees($nowDate);

        if (empty($employees)) {
            throw new RuntimeException('none of employees have to calculate annual leave');
        }

        foreach ($employees as $employee) {

            $leaveMinutes = 0;
            $annualYear = $nowDate->format('Y');
            $onBoardDate = $employee->getOnBoardDate();

            $onBoardDateTime = new DateTime($onBoardDate);
            $onBoardDateTime->setTime(0, 0);

            if ($nowDate <= $onBoardDateTime) {
                continue;
            }

            $dDiff = $onBoardDateTime->diff($nowDate);
            $diffYear = $dDiff->format('%y');

            if ($diffYear < 1 && $dDiff->format('%m') < 6) {
                continue;
            }

            if ($diffYear < 1 && $dDiff->format('%m') >= 6) {
                $annualYear = $onBoardDateTime->format('Y');
                $leaveMinutes = 3 * 8 * 60;
            } else if ($onBoardDateTime->format('md') === $nowDate->format('md')) {

                if ($diffYear > 25) {
                    $leaveMinutes = 30 * 8 * 60;
                } else {
                    $leaveMinutes = $this->leaveMap[$diffYear] * 8 * 60;
                }
            }

            if ($leaveMinutes !== 0) {

                echo "{$employee->getEmployeeId()->value()}:{$diffYear}:{$employee->getOnBoardDate()}:{$leaveMinutes}\n";

                $leaveRecord = new LeaveRecord(
                    $employee->getEmployeeId(),
                    $annualYear,
                    [new AnnualLeave($leaveMinutes)]
                );

                $employeeLeave = new EmployeeLeave(
                    $employee->getEmployeeId(),
                    [new AnnualLeave($leaveMinutes)]
                );

                $this->employeeLeaveRepository->resetLeave($employeeLeave);
                $this->employeeLeaveRepository->saveRecord($leaveRecord);
            }
        }
    }
}