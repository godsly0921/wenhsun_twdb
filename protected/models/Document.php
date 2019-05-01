<?php

declare(strict_types=1);

class Document extends CActiveRecord
{
    public function tableName()
    {
        return 'document';
    }

    public function rules()
    {
        return [
            ['receiver', 'required'],
            ['title', 'required'],
            ['send_text_number', 'required'],
            ['send_text_date', 'required'],
            ['document_file', 'required'],
        ];
    }

    public function relations()
    {
        return [];
    }

    public function scopes() {
        return array(
            'byUpdateAt' => array('order' => 'update_at DESC'),
        );
    }
}