<?php

/**
 * This is the model class for table "power".
 *
 * The followings are the available columns in table 'power':
 * @property integer $id
 * @property integer $power_number
 * @property string $power_name
 * @property string $power_controller
 * @property integer $power_master_number
 * @property integer $power_range
 * @property integer $power_display
 */
class Power extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'power';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('power_name, power_master_number, power_range', 'required','message' => '請輸入{attribute}'),
			array('power_number, power_master_number, power_range, power_display', 'numerical', 'integerOnly'=>true),
			array('power_name', 'length', 'max'=>50),
			array('power_controller', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, power_number, power_name, power_controller, power_master_number, power_range, power_display', 'safe', 'on'=>'search'),
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
			'power_number' => 'Power Number',
			'power_name' => 'Power Name',
			'power_controller' => 'Power Controller',
			'power_master_number' => 'Power Master Number',
			'power_range' => 'Power Range',
			'power_display' => 'Power Display',
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
		$criteria->compare('power_number',$this->power_number);
		$criteria->compare('power_name',$this->power_name,true);
		$criteria->compare('power_controller',$this->power_controller,true);
		$criteria->compare('power_master_number',$this->power_master_number);
		$criteria->compare('power_range',$this->power_range);
		$criteria->compare('power_display',$this->power_display);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Power the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
