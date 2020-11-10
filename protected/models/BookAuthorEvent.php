<?php

/**
 * This is the model class for table "event_list".
 *
 * The followings are the available columns in table 'event_list':
 * @property integer $id
 * @property integer $book_author_id
 * @property string $year
 * @property string $month
 * @property string $day
 * @property string $title
 * @property string $description
 * @property string $image_link
 * @property string $create_at
 * @property string $update_at
 */
class BookAuthorEvent extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'book_author_event';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('book_author_id, create_at, update_at', 'required'),
            array('book_author_id', 'numerical', 'integerOnly'=>true),
            array('year', 'length', 'max'=>4),
            array('month, day', 'length', 'max'=>2),
            array('title', 'length', 'max'=>200),
            array('image_link', 'length', 'max'=>512),
            array('description', 'safe'),
            // The following rule is used by search().
            array('id, book_author_id, year, month, day, title', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'book_author_id' => '作者 id',
            'year' => '年表-年',
            'month' => '年表-月',
            'day' => '年表-日',
            'title' => '事件標題',
            'description' => '事件說明',
            'image_link' => '圖庫圖片',
            'create_at' => '建立時間',
            'update_at' => '更新時間',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('book_author_id',$this->book_author_id);
        $criteria->compare('year',$this->year,true);
        $criteria->compare('month',$this->month,true);
        $criteria->compare('day',$this->day,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('image_link',$this->image_link,true);
        $criteria->compare('create_at',$this->create_at,true);
        $criteria->compare('update_at',$this->update_at,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return EventList the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
