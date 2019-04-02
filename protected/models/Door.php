<?php

/**
 * This is the model class for table "door".
 *
 * The followings are the available columns in table 'door':
 * @property integer $id
 * @property string $name
 * @property string $en_name
 * @property string $position
 * @property integer $status
 * @property string $station
 * @property string $ip
 * @property string $create_date
 * @property string $edit_date
 */
class Door extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'door';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, en_name, position, status, station, create_date, edit_date', 'required'),
			array('id, status', 'numerical', 'integerOnly'=>true),
			array('name, en_name', 'length', 'max'=>100),
			array('position, station', 'length', 'max'=>20),
			array('ip', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, en_name, position, status, station, ip, create_date, edit_date', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'en_name' => 'En Name',
			'position' => 'Position',
			'status' => 'Status',
			'station' => 'Station',
			'ip' => 'Ip',
			'create_date' => 'Create Date',
			'edit_date' => 'Edit Date',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('en_name',$this->en_name,true);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('station',$this->station,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('edit_date',$this->edit_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Door the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
