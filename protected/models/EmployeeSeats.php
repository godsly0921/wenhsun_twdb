<?php

class EmployeeSeats extends CActiveRecord
{
    public function tableName()
    {
        return 'employee_seats';
    }

    public function rules()
    {
        return [
            ['seat_name', 'required'],
            ['seat_name', 'length', 'max' => 64],
            ['seat_number', 'required'],
            ['seat_number', 'length', 'max' => 8],
        ];
    }

    public function relations()
    {
        return [];
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function scopes() {
        return array(
            'byUpdateAt' => ['order' => 'update_at DESC'],
            'bySeatNumber' => ['order' => 'seat_number ASC'],
        );
    }
}