<?php

declare(strict_types=1);

class SalaryReport extends CActiveRecord
{
    public function tableName()
    {
        return 'salary_report';
    }

    public function rules()
    {
        return [
            ['year', 'required'],
            ['month', 'required'],
        ];
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function scopes() {
        return array(
            'byUpdateAt' => array('order' => 'update_at DESC'),
        );
    }

}