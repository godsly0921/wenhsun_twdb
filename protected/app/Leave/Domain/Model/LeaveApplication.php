<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

class LeaveApplication
{
    private $appId;
    private $applyEmployeeId;
    private $applyDate;
    private $startDate;
    private $endDate;
    private $status;
    private $confirmDate;
    private $leaveComposition;

    public function __construct(LeaveApplicationId $appId)
    {
        $this->appId = $appId;
    }

    public function apply(
        EmployeeId $employeeId,
        string $startDate,
        string $endDate,
        array $leaveComposition
    ) {
        $this->applyEmployeeId = $employeeId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->applyDate = date('Y-m-d H:i:s');
        $this->status = LeaveApplicationStatus::APPLY;
        $this->leaveComposition = $leaveComposition;
    }
}