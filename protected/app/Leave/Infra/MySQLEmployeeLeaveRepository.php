<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Infra;

use CLogger;
use Common;
use RuntimeException;
use Throwable;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Model\EmployeeLeaveRepository;
use Wenhsun\Leave\Domain\Model\Leave;
use Leave as LeaveORM;
use Yii;

class MySQLEmployeeLeaveRepository implements EmployeeLeaveRepository
{
    public function save(Leave $leave): void
    {
        try {

            $now = Common::now();
            $leaveORM = new LeaveORM();
            $leaveORM->annual_year = $leave->getAnnualYear();
            $leaveORM->employee_id = $leave->getEmployeeId()->value();
            $leaveORM->type = $leave->getLeaveType();
            $leaveORM->status = $leave->getStatus();
            $leaveORM->minutes = $leave->getMinutes();
            $leaveORM->create_at = $now;
            $leaveORM->update_at = $now;

            $leaveORM->save();

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            throw new RuntimeException($ex->getMessage());
        }
    }

    public function isSetAnnualLeave(EmployeeId $employeeId, string $annualYear): bool
    {
        $sql = "
            SELECT 1 FROM `leave`
            WHERE annual_year = :annual_year
            AND employee_id = :employee_id
        ";

        $binds = [
            ':annual_year' => $annualYear,
            ':employee_id' => $employeeId->value()
        ];

        $r = Yii::app()->db->createCommand($sql)->bindValues($binds)->queryAll();

        if (empty($r)) {
            return false;
        }

        return true;
    }
}