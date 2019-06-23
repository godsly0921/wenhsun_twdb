<?php

declare(strict_types=1);

class Leave extends CActiveRecord
{
    public function tableName()
    {
        return 'leave';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}