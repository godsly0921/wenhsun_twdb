<?php

/**
 * This is the model class for table "level_one_discount".
 *
 * The followings are the available columns in table 'level_one_discount':
 * @property integer $id
 * @property integer $device_id
 * @property integer $level_one_id
 * @property string $weeks
 * @property integer $start_hors
 * @property integer $start_minute
 * @property integer $end_hors
 * @property integer $end_minute
 * @property integer $discount
 * @property integer $builder
 * @property string $create_time
 * @property string $edit_time
 */
class Levelonediscount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'level_one_discount';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('device_id, level, weeks, start_hors, start_minute, end_hors, end_minute, discount, builder, create_time, edit_time', 'required'),
			array('device_id, start_hors, start_minute, end_hors, end_minute, discount, builder', 'numerical', 'integerOnly'=>true),
			array('level,weeks', 'length', 'max'=>1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, device_id, level_one_id, weeks, start_hors, start_minute, end_hors, end_minute, discount, builder, create_time, edit_time', 'safe', 'on'=>'search'),
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
			'device_id' => 'Device',
			'level_one_id' => 'Level One',
			'weeks' => 'Weeks',
			'start_hors' => 'Start Hors',
			'start_minute' => 'Start Minute',
			'end_hors' => 'End Hors',
			'end_minute' => 'End Minute',
			'discount' => 'Discount',
			'builder' => 'Builder',
			'create_time' => 'Create Time',
			'edit_time' => 'Edit Time',
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
		$criteria->compare('device_id',$this->device_id);
		$criteria->compare('level_one_id',$this->level_one_id);
		$criteria->compare('weeks',$this->weeks,true);
		$criteria->compare('start_hors',$this->start_hors);
		$criteria->compare('start_minute',$this->start_minute);
		$criteria->compare('end_hors',$this->end_hors);
		$criteria->compare('end_minute',$this->end_minute);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('builder',$this->builder);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('edit_time',$this->edit_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Levelonediscount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
