<?php

declare(strict_types=1);

class LeaveApplyORM extends CActiveRecord
{
    public function tableName(): string
    {
        return 'leave_apply';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}