<?php

declare(strict_types=1);

class LeaveRecord extends CActiveRecord
{
    public function tableName()
    {
        return 'leave_record';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}