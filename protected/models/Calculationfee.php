<?php

/**
 * This is the model class for table "calculation_fee".
 *
 * The followings are the available columns in table 'calculation_fee':
 * @property integer $id
 * @property integer $device_id
 * @property integer $level_one_id
 * @property integer $base_minute
 * @property integer $base_charge
 * @property double $start_base_charge
 * @property double $max_use_base
 * @property double $unused_base
 * @property integer $builder
 * @property string $create_time
 * @property string $edit_time
 */
class Calculationfee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'calculation_fee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('device_id, level_one_id, base_minute, base_charge, start_base_charge, max_use_base, unused_base, builder, create_time, edit_time', 'required'),
			array('device_id, level_one_id, base_minute, base_charge, builder', 'numerical', 'integerOnly'=>true),
			array('start_base_charge, max_use_base, unused_base', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, device_id, level_one_id, base_minute, base_charge, start_base_charge, max_use_base, unused_base, builder, create_time, edit_time', 'safe', 'on'=>'search'),
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
			'base_minute' => 'Base Minute',
			'base_charge' => 'Base Charge',
			'start_base_charge' => 'Start Base Charge',
			'max_use_base' => 'Max Use Base',
			'unused_base' => 'Unused Base',
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
		$criteria->compare('base_minute',$this->base_minute);
		$criteria->compare('base_charge',$this->base_charge);
		$criteria->compare('start_base_charge',$this->start_base_charge);
		$criteria->compare('max_use_base',$this->max_use_base);
		$criteria->compare('unused_base',$this->unused_base);
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
	 * @return Calculationfee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
