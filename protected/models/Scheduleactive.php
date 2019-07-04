<?php

/**
 * This is the model class for table "schedule_active".
 *
 * The followings are the available columns in table 'schedule_active':
 * @property integer $active_id
 * @property string $active_name
 * @property string $active_date
 * @property string $update_date
 * @property integer $update_id
 */
class Scheduleactive extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'schedule_active';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('active_name, active_date, update_date, update_id', 'required'),
			array('update_id', 'numerical', 'integerOnly'=>true),
			array('active_name', 'length', 'max'=>125),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('active_id, active_name, active_date, update_date, update_id', 'safe', 'on'=>'search'),
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
			'active_id' => '流水號',
			'active_name' => '活動名稱',
			'active_date' => '活動日期',
			'update_date' => '更新時間',
			'update_id' => '更新人員',
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

		$criteria->compare('active_id',$this->active_id);
		$criteria->compare('active_name',$this->active_name,true);
		$criteria->compare('active_date',$this->active_date,true);
		$criteria->compare('update_date',$this->update_date,true);
		$criteria->compare('update_id',$this->update_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Scheduleactive the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
