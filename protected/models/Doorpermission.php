<?php

/**
 * This is the model class for table "door_permission".
 *
 * The followings are the available columns in table 'door_permission':
 * @property integer $id
 * @property integer $type
 * @property string $name
 * @property string $weeks
 * @property string $start_hors
 * @property string $start_minute
 * @property string $end_hors
 * @property string $end_minute
 * @property integer $builder
 * @property string $create_time
 */
class Doorpermission extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'door_permission';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, type, name, weeks, start_hors, start_minute, end_hors, end_minute, builder, create_time', 'required'),
			array('id, type, builder', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>32),
			array('start_hors, start_minute, end_hors, end_minute', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, name, weeks, start_hors, start_minute, end_hors, end_minute, builder, create_time', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'name' => 'Name',
			'weeks' => 'Weeks',
			'start_hors' => 'Start Hors',
			'start_minute' => 'Start Minute',
			'end_hors' => 'End Hors',
			'end_minute' => 'End Minute',
			'builder' => 'Builder',
			'create_time' => 'Create Time',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('weeks',$this->weeks,true);
		$criteria->compare('start_hors',$this->start_hors,true);
		$criteria->compare('start_minute',$this->start_minute,true);
		$criteria->compare('end_hors',$this->end_hors,true);
		$criteria->compare('end_minute',$this->end_minute,true);
		$criteria->compare('builder',$this->builder);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Doorpermission the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
