<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Service;

use Wenhsun\Leave\Domain\Model\AnnualLeaveType;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Model\LeaveApply\Application;
use Wenhsun\Leave\Domain\Model\LeaveApply\LeaveApplicationId;
use Wenhsun\Leave\Domain\Model\LeaveApply\LeaveApplicationStatusEnum;
use Wenhsun\Leave\Domain\Model\LeaveType;

class LeaveApplyDomainService
{
    private const DAY_IN_SECOND = 86400;
    private const WORKING_DAY_HOUR_IN_SECOND = 28800;
    private const THREE_HOURS_IN_SECOND = 10800;
    private const ONE_HOUR_IN_SECOND = 3600;

    public function apply(
        EmployeeId $employeeId,
        string $startDate,
        string $endDate,
        string $type,
        string $memo,
        ?string $fileLocation = null
    ): Application {

        return new Application(
            new LeaveApplicationId(),
            $employeeId,
            $startDate,
            $endDate,
            LeaveApplicationStatusEnum::APPLY,
            $this->genLeaveFromType($type),
            $memo,
            $fileLocation
        );
    }

    private function genLeaveFromType(string $type): LeaveType
    {
        switch ($type) {
            case 'ANNUAL':
                $leaveType = new AnnualLeaveType();
                break;
            default:
                $leaveType = '';
        }

        return $leaveType;
    }

    public function calcMinutes(string $startDate, string $endDate): int
    {
        $sTimestamp = strtotime($startDate);
        $eTimestamp = strtotime($endDate);

        $timestampDiff = $eTimestamp - $sTimestamp;

        $daysInSecond = floor($timestampDiff / self::DAY_IN_SECOND);
        $minutes = (int) $daysInSecond * self::WORKING_DAY_HOUR_IN_SECOND / 60;

        $secondsOfLessADay = $timestampDiff % self::DAY_IN_SECOND;

        if ($secondsOfLessADay > self::THREE_HOURS_IN_SECOND) {
            $secondsOfLessADay -= self::ONE_HOUR_IN_SECOND;
        }

        $minutesOfLessADay = $secondsOfLessADay / 60;

        return $minutes + $minutesOfLessADay;
    }
}