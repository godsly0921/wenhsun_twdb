<?php

/**
 * This is the model class for table "image_queue".
 *
 * The followings are the available columns in table 'image_queue':
 * @property integer $image_queue_id
 * @property integer $single_id
 * @property string $size_type
 * @property integer $queue_status
 * @property string $create_time
 * @property string $done_time
 */
class Imagequeue extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'image_queue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('single_id, size_type, create_time', 'required'),
			array('single_id, queue_status', 'numerical', 'integerOnly'=>true),
			array('size_type', 'length', 'max'=>20),
			array('done_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('image_queue_id, single_id, size_type, queue_status, create_time, done_time', 'safe', 'on'=>'search'),
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
			'image_queue_id' => '流水號',
			'single_id' => '圖片編號',
			'size_type' => '尺寸類型',
			'queue_status' => '佇列處理狀態',
			'create_time' => '進入佇列時間',
			'done_time' => '佇列處理時間',
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

		$criteria->compare('image_queue_id',$this->image_queue_id);
		$criteria->compare('single_id',$this->single_id);
		$criteria->compare('size_type',$this->size_type,true);
		$criteria->compare('queue_status',$this->queue_status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('done_time',$this->done_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Imagequeue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
