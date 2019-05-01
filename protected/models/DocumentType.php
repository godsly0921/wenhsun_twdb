<?php

declare(strict_types=1);

class DocumentType extends CActiveRecord
{
    public function tableName()
    {
        return 'document_type';
    }

    public function rules()
    {
        return [];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function relations()
    {
        return [];
    }
}