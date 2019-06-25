<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Application;

use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Service\LeaveApplyDomainService;

class LeaveApplyService
{
    private $domainServ;

    public function __construct(LeaveApplyDomainService $domainServ)
    {
        $this->domainServ = $domainServ;
    }

    public function applyLeave(LeaveApplyCommand $applyCommand): void
    {
        //start a transaction

        $employeeId = new EmployeeId($applyCommand->employeeId);

        $application = $this->domainServ->apply(
            $employeeId,
            $applyCommand->startDate,
            $applyCommand->endDate,
            $applyCommand->type,
            $applyCommand->memo,
            $applyCommand->fileLocation
        );

        //repo insert

    }
}