<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Infra;

use Common;
use Wenhsun\Leave\Domain\Model\EmployeeLeave;
use Wenhsun\Leave\Domain\Model\EmployeeLeaveRepository;
use Wenhsun\Leave\Domain\Model\Leave;
use Wenhsun\Leave\Domain\Model\LeaveRecord;
use LeaveRecord as LeaveRecordORM;
use EmployeeLeave as EmployeeLeaveORM;
use Wenhsun\Tool\Uuid;

class MySQLEmployeeLeaveRepository implements EmployeeLeaveRepository
{
    /**
     * @param EmployeeLeave $employeeLeave
     * @throws \CDbException
     */
    public function resetLeave(EmployeeLeave $employeeLeave): void
    {
        $now = Common::now();

        foreach ($employeeLeave->fetchLeaves() as $leave) {

            /** @var $leave Leave */

            $employeeLeaveData = EmployeeLeaveORM::model()->find([
                'condition' => 'employee_id = :employee_id and type = :type',
                'params' => [
                    ':employee_id' => $employeeLeave->getEmployeeId()->value(),
                    ':type' => $leave->getType(),
                ]
            ]);

            if ($employeeLeaveData === null) {
                $employeeLeaveData = new EmployeeLeaveORM();
                $employeeLeaveData->id = Uuid::gen();
                $employeeLeaveData->employee_id = $employeeLeave->getEmployeeId()->value();
                $employeeLeaveData->type = $leave->getType();
                $employeeLeaveData->create_at = $now;
            }

            $employeeLeaveData->minutes += $leave->getMinutes();
            $employeeLeaveData->update_at = $now;
            $employeeLeaveData->save();
        }
    }

    public function saveRecord(LeaveRecord $leaveRecord): void
    {
        $now = Common::now();

        foreach ($leaveRecord->getLeaves() as $leave) {
            /** @var $leave Leave */

            $leaveORM = new LeaveRecordORM();
            $leaveORM->id = Uuid::gen();
            $leaveORM->annual_year = $leaveRecord->getAnnualYear();
            $leaveORM->employee_id = $leaveRecord->getEmployeeId()->value();
            $leaveORM->type = $leave->getType();
            $leaveORM->minutes = $leave->getMinutes();
            $leaveORM->create_at = $now;
            $leaveORM->update_at = $now;

            $leaveORM->save();

        }
    }
}