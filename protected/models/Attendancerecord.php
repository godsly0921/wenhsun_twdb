<?php

/**
 * This is the model class for table "attendance_record".
 *
 * The followings are the available columns in table 'attendance_record':
 * @property string $id
 * @property integer $employee_id
 * @property string $day
 * @property string $first_time
 * @property string $last_time
 * @property integer $abnormal_type
 * @property string $abnormal
 * @property string $create_at
 * @property string $update_at
 */
class Attendancerecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'attendance_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, day, first_time, last_time, abnormal_type, create_at, update_at', 'required'),
			array('abnormal_type', 'numerical', 'integerOnly'=>true),
			array('abnormal', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employee_id, day, first_time, last_time, abnormal_type, abnormal, create_at, update_at', 'safe', 'on'=>'search'),
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
			'employee_id' => 'Employee',
			'day' => 'Day',
			'first_time' => 'First Time',
			'last_time' => 'Last Time',
			'abnormal_type' => 'Abnormal Type',
			'abnormal' => 'Abnormal',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('first_time',$this->first_time,true);
		$criteria->compare('last_time',$this->last_time,true);
		$criteria->compare('abnormal_type',$this->abnormal_type);
		$criteria->compare('abnormal',$this->abnormal,true);
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
	 * @return Attendancerecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
