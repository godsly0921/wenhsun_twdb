<?php

declare(strict_types=1);

class LeaveApplyHistORM extends CActiveRecord
{
    public function tableName(): string
    {
        return 'leave_apply_hist';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}