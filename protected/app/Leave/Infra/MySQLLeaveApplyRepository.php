<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Infra;

use Common;
use LeaveApplyHistORM;
use LeaveApplyORM;
use Wenhsun\Leave\Domain\Model\LeaveApply\Application;
use Wenhsun\Leave\Domain\Model\LeaveApply\LeaveApplyRepository;
use Wenhsun\Tool\Uuid;

class MySQLLeaveApplyRepository implements LeaveApplyRepository
{
    public function save(Application $application): void
    {
        $now = Common::now();
        $leaveApplyORM = new LeaveApplyORM();
        $leaveApplyORM->application_id = $application->getApplicationId()->value();
        $leaveApplyORM->employee_id = $application->getEmployeeId()->value();
        $leaveApplyORM->leave_type = $application->getLeaveType()->type();
        $leaveApplyORM->leave_status = $application->getStatus();
        $leaveApplyORM->leave_start_date = $application->getStartDate();
        $leaveApplyORM->leave_end_date = $application->getEndDate();
        $leaveApplyORM->leave_minutes = $application->calcMinutes();
        $leaveApplyORM->leave_memo = $application->getMemo();
        $leaveApplyORM->leave_file_location = $application->getFileLocation();
        $leaveApplyORM->create_at = $now;
        $leaveApplyORM->update_at = $now;
        $leaveApplyORM->save();

        $leaveApplyHistORM = new LeaveApplyHistORM();
        $leaveApplyHistORM->application_hist_id = Uuid::gen();
        $leaveApplyHistORM->act_employee_id = $leaveApplyORM->employee_id;
        $leaveApplyHistORM->leave_status = $leaveApplyORM->leave_status;
        $leaveApplyHistORM->create_at = $now;
        $leaveApplyHistORM->save();
    }
}