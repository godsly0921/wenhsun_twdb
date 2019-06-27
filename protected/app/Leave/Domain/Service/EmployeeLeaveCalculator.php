<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Service;

use DateTime;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\Minute;

class EmployeeLeaveCalculator
{
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

    public function calcAnnualLeaveInRecentYear(DateTime $nowDate, Employee $employee): Minute
    {
        $nowDate->setTime(0, 0);

        $onBoardDate = $employee->getOnBoardDate();

        $onBoardDateTime = new DateTime($onBoardDate);
        $onBoardDateTime->setTime(0, 0);

        $dDiff = $nowDate->diff($onBoardDateTime);
        $diffYear = $dDiff->format('%y');

        if ($diffYear < 1 && $dDiff->format('%m') < 6) {
            return new Minute(0);
        }

        if ($diffYear < 1 && $dDiff->format('%m') >= 6) {
            return new Minute(3 * 8 * 60);
        }

        if (isset($this->leaveMap[$diffYear])) {
            return new Minute($this->leaveMap[$diffYear] * 8 * 60);
        }

        return new Minute(30 * 8 * 60);
    }
}