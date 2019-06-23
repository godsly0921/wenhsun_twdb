<?php

class About extends CActiveRecord
{
    public function tableName()
    {
        return 'about';
    }

    public function rules()
    {
        return array(
            array('title, description, update_time, update_account_id', 'required'),
            array('update_account_id', 'numerical', 'integerOnly' => true),
            array('image', 'length', 'max' => 200),
            array('title, description, update_time, update_account_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'id',
            'title' => '項目',
            'description' => '項目說明',
            'image' => '圖片',
            'paragraph' => '文字內容',
            'update_time' => '更新時間',
            'update_account_id' => '更新人員ID',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('paragraph', $this->paragraph, true);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_account_id', $this->update_account_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
