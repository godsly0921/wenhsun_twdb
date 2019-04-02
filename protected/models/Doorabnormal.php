<?php

/**
 * This is the model class for table "door_abnormal".
 *
 * The followings are the available columns in table 'door_abnormal':
 * @property integer $id
 * @property string $date
 * @property string $user_name
 * @property string $station_name
 * @property string $card_number
 * @property string $card_time
 * @property string $exception_description
 */
class Doorabnormal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'door_abnormal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, user_name, station_name, card_number, card_time, exception_description', 'required'),
			array('user_name', 'length', 'max'=>32),
			array('station_name', 'length', 'max'=>128),
			array('card_number', 'length', 'max'=>20),
			array('exception_description', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, user_name, station_name, card_number, card_time, exception_description', 'safe', 'on'=>'search'),
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
			'date' => 'Date',
			'user_name' => 'User Name',
			'station_name' => 'Station Name',
			'card_number' => 'Card Number',
			'card_time' => 'Card Time',
			'exception_description' => 'Exception Description',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('station_name',$this->station_name,true);
		$criteria->compare('card_number',$this->card_number,true);
		$criteria->compare('card_time',$this->card_time,true);
		$criteria->compare('exception_description',$this->exception_description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Doorabnormal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
