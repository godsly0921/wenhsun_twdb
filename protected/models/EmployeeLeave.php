<?php

declare(strict_types=1);

class EmployeeLeave extends CActiveRecord
{
    public function tableName()
    {
        return 'employee_leave';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}