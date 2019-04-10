<?php

declare(strict_types=1);

class Author extends CActiveRecord
{
    public function tableName()
    {
        return 'author';
    }

    public function rules()
    {
        return [
            ['author_name', 'required'],
            ['gender', 'required'],
            ['birth', 'required'],
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
            'byUpdateAt' => array('order' => 'update_at DESC'),
        );
    }
}