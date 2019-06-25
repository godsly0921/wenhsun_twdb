<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model\LeaveApply;

use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Model\Leave;
use Wenhsun\Leave\Domain\Model\LeaveType;

class Application
{
    private const DAY_IN_SECOND = 86400;
    private const WORKING_DAY_HOUR_IN_SECOND = 28800;
    private const THREE_HOURS_IN_SECOND = 10800;
    private const ONE_HOUR_IN_SECOND = 3600;

    private $applicationId;
    private $employeeId;
    private $startDate;
    private $endDate;
    private $status;
    private $leaveType;
    private $memo;
    private $fileLocation;

    public function __construct (
        LeaveApplicationId $applicationId,
        EmployeeId $employeeId,
        string $startDate,
        string $endDate,
        string $status,
        LeaveType $leaveType,
        string $memo,
        ?string $fileLocation = null
    ) {
        $this->applicationId = $applicationId;
        $this->employeeId = $employeeId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->leaveType = $leaveType;
        $this->memo = $memo;
        $this->fileLocation = $fileLocation;
    }

    /**
     * @return LeaveApplicationId
     */
    public function getApplicationId(): LeaveApplicationId
    {
        return $this->applicationId;
    }

    /**
     * @return EmployeeId
     */
    public function getEmployeeId(): EmployeeId
    {
        return $this->employeeId;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @return string
     */
    public function getEndDate(): string
    {
        return $this->endDate;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return LeaveType
     */
    public function getLeaveType(): LeaveType
    {
        return $this->leaveType;
    }

    /**
     * @return string
     */
    public function getMemo(): string
    {
        return $this->memo;
    }

    /**
     * @return string
     */
    public function getFileLocation(): ?string
    {
        return $this->fileLocation;
    }

    public function calcMinutes(): int
    {
        $sTimestamp = strtotime($this->startDate);
        $eTimestamp = strtotime($this->endDate);

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