<?php

/**
 * This is the model class for table "black_record".
 *
 * The followings are the available columns in table 'black_record':
 * @property integer $id
 * @property string $use_date
 * @property string $use_period
 * @property string $user_name
 * @property string $card_number
 * @property string $device_name
 * @property string $occupied_periods
 * @property string $be_occupied
 */
class Blackrecord extends CActiveRecord
{

	public $total;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'black_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('use_date, use_period, user_name, card_number, device_name, occupied_periods, be_occupied', 'required'),
			//array('use_period, occupied_periods', 'length', 'max'=>12),
			//array('user_name, be_occupied', 'length', 'max'=>32),
			//array('card_number', 'length', 'max'=>10),
			//array('device_name', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, use_date, use_period, user_name, card_number, device_name, occupied_periods, be_occupied', 'safe', 'on'=>'search'),
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
			'use_date' => 'Use Date',
			'use_period' => 'Use Period',
			'user_name' => 'User Name',
			'card_number' => 'Card Number',
			'device_name' => 'Device Name',
			'occupied_periods' => 'Occupied Periods',
			'be_occupied' => 'Be Occupied',
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
		$criteria->compare('use_date',$this->use_date,true);
		$criteria->compare('use_period',$this->use_period,true);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('card_number',$this->card_number,true);
		$criteria->compare('device_name',$this->device_name,true);
		$criteria->compare('occupied_periods',$this->occupied_periods,true);
		$criteria->compare('be_occupied',$this->be_occupied,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Blackrecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
