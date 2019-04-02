<?php

/**
 * This is the model class for table "device_record".
 *
 * The followings are the available columns in table 'device_record':
 * @property integer $id
 * @property string $day_num
 * @property string $use_date
 * @property string $station
 * @property string $num
 * @property string $name
 * @property string $dep1
 * @property string $dep2
 * @property string $wnum
 * @property string $des
 * @property string $detail
 * @property string $card
 * @property integer $tobill
 * @property string $create_date
 */
class Device_record extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'device_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('day_num, use_date, station, num, dep1, dep2,  des, detail, card, create_date', 'required'),
			array('tobill', 'numerical', 'integerOnly'=>true),
			array('day_num, station, num', 'length', 'max'=>20),
			array('name, dep1, dep2, wnum', 'length', 'max'=>100),
			array('detail, card', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, day_num, use_date, station, num, name, dep1, dep2, wnum, des, detail, card, tobill, create_date', 'safe', 'on'=>'search'),
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
			'day_num' => 'Day Num',
			'use_date' => 'Use Date',
			'station' => 'Station',
			'num' => 'Num',
			'name' => 'Name',
			'dep1' => 'Dep1',
			'dep2' => 'Dep2',
			'wnum' => 'Wnum',
			'des' => 'Des',
			'detail' => 'Detail',
			'card' => 'Card',
			'tobill' => 'Tobill',
			'create_date' => 'Create Date',
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
		$criteria->compare('day_num',$this->day_num,true);
		$criteria->compare('use_date',$this->use_date,true);
		$criteria->compare('station',$this->station,true);
		$criteria->compare('num',$this->num,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('dep1',$this->dep1,true);
		$criteria->compare('dep2',$this->dep2,true);
		$criteria->compare('wnum',$this->wnum,true);
		$criteria->compare('des',$this->des,true);
		$criteria->compare('detail',$this->detail,true);
		$criteria->compare('card',$this->card,true);
		$criteria->compare('tobill',$this->tobill);
		$criteria->compare('create_date',$this->create_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Device_record the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
