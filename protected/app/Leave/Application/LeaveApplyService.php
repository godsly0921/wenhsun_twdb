<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Application;

use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Model\LeaveApply\LeaveApplyRepository;
use Wenhsun\Leave\Domain\Service\LeaveApplyDomainService;

class LeaveApplyService
{
    private $leaveApplyRepository;

    public function __construct(LeaveApplyRepository $leaveApplyRepository)
    {
        $this->leaveApplyRepository = $leaveApplyRepository;
    }

    public function applyLeave(LeaveApplyCommand $applyCommand): void
    {
        //start a transaction

        $domainServ = new LeaveApplyDomainService();
        $employeeId = new EmployeeId($applyCommand->employeeId);

        $application = $domainServ->apply(
            $employeeId,
            $applyCommand->startDate,
            $applyCommand->endDate,
            $applyCommand->type,
            $applyCommand->memo,
            $applyCommand->fileLocation
        );

        $this->leaveApplyRepository->save($application);
    }
}