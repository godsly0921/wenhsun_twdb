<?php

class ActivityNews extends CActiveRecord
{
    public function tableName()
    {
        return 'activity_news';
    }

    public function rules()
    {
        return array(
            array('title, image, main_content, active', 'required'),
            array('create_by, update_by', 'numerical', 'integerOnly' => true),
            array('image', 'length', 'max' => 200),
            array('active', 'safe', 'on' => 'search'),
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
            'title' => '標題',
            'second_title' => '副標',
            'content' => '內文',
            'main_content' => '主要內文',
            'image' => '圖片',
            'active' => '是否上架',
            'create_at' => '建立時間',
            'update_at' => '更新時間',
            'create_by' => '建立人員',
            'update_by' => '更新人員'
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('active', $this->title, true);
        $criteria->compare('create_at', $this->description, true);
        $criteria->compare('update_at', $this->image, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
