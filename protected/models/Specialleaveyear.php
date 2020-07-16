<?php

/**
 * This is the model class for table "special_leave_year".
 *
 * The followings are the available columns in table 'special_leave_year':
 * @property integer $id
 * @property string $employee_id
 * @property string $start_date
 * @property string $end_date
 * @property double $seniority
 * @property integer $special_leave
 * @property integer $is_close
 */
class Specialleaveyear extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'special_leave_year';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, start_date, end_date, seniority, special_leave', 'required'),
			array('special_leave, is_close', 'numerical', 'integerOnly'=>true),
			array('seniority', 'numerical'),
			array('employee_id', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employee_id, start_date, end_date, seniority, special_leave, is_close', 'safe', 'on'=>'search'),
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
			'employee_id' => 'FK employee.id員工id',
			'start_date' => '特休開始時間(yyyy-mm-dd)',
			'end_date' => '特休結束時間(yyyy-mm-dd)',
			'seniority' => '年資(月)',
			'special_leave' => '特休假總數(分鐘)',
			'is_close' => '是否已結算(0:否 1:是)',
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
		$criteria->compare('employee_id',$this->employee_id,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('seniority',$this->seniority);
		$criteria->compare('special_leave',$this->special_leave);
		$criteria->compare('is_close',$this->is_close);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Specialleaveyear the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
