<?php

declare(strict_types=1);

class AuthorBank extends CActiveRecord
{
    public function tableName()
    {
        return 'author_bank';
    }

    public function rules()
    {
        return [];
    }

    public function relations()
    {
        return [];
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}