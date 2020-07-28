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
        /*    ['author_name, gender', 'required', 'message' => '請輸入 {attribute}'],
            ['author_name', 'length', 'min' => 1, 'max' => 50, 'tooLong' => '姓名最多為50個字元'],
            ['job_title', 'length', 'min' => 1, 'max' => 1, 'tooLong' => '職稱最多為100個字元'],
            ['service', 'length', 'min' => 1, 'max' => 1, 'tooLong' => '服務單位最多為100個字元'],
            ['residence_address', 'length', 'min' => 1, 'max' => 1, 'tooLong' => '戶籍地最多為200個字元'],
            ['nationality', 'length', 'min' => 1, 'max' => 1, 'tooLong' => '國籍最多為50個字元'],*/
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