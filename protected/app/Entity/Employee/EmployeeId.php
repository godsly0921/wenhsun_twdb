<?php

declare(strict_types=1);

namespace Wenhsun\Entity\Employee;

use Yii;

class EmployeeId
{
    private $employeeId;

    public function __construct()
    {
        Yii::app()->db->createCommand(
            "
                UPDATE employee_seq
                SET current_val = LAST_INSERT_ID(current_val + 1)
            "
        )->execute();

        $id = Yii::app()->db->getLastInsertID();
        $id = "EP" . date("Ym") . str_pad($id, 4, "0", STR_PAD_LEFT);

        $this->setEmployeeId($id);
    }

    private function setEmployeeId($id)
    {
        $this->employeeId = $id;
    }

    public function employeeId(): string
    {
        return $this->employeeId;
    }
}