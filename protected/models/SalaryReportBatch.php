<?php

declare(strict_types=1);

class SalaryReportBatch extends CActiveRecord
{
    public function tableName()
    {
        return 'salary_report_batch';
    }

    public function rules()
    {
        return [];
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