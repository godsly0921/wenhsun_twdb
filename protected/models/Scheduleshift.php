<?php

/**
 * This is the model class for table "schedule_shift".
 *
 * The followings are the available columns in table 'schedule_shift':
 * @property integer $shift_id
 * @property integer $store_id
 * @property integer $in_out
 * @property string $class
 * @property integer $is_special
 * @property string $start_time
 * @property string $end_time
 * @property string $start_date
 * @property string $end_date
 * @property string $update_time
 * @property integer $update_id
 */
class Scheduleshift extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'schedule_shift';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('store_id, in_out, class, is_special, start_time, end_time, update_time, update_id', 'required'),
			array('store_id, in_out, is_special, update_id', 'numerical', 'integerOnly'=>true),
			array('class', 'length', 'max'=>2),
			array('start_date, end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('shift_id, store_id, in_out, class, is_special, start_time, end_time, start_date, end_date, update_time, update_id', 'safe', 'on'=>'search'),
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
			'shift_id' => '流水號',
			'store_id' => '館別 1:書店 2:茶館',
			'in_out' => '場別 1:內場 2:外場 0:不分',
			'class' => '班別 A、B',
			'is_special' => '是否為特殊上班時間 0:否 1:是',
			'start_time' => '上班時間',
			'end_time' => '下班時間',
			'start_date' => '特殊上班時間開始日期',
			'end_date' => '特殊上班時間結束日期',
			'update_time' => '更新時間',
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

		$criteria->compare('shift_id',$this->shift_id);
		$criteria->compare('store_id',$this->store_id);
		$criteria->compare('in_out',$this->in_out);
		$criteria->compare('class',$this->class,true);
		$criteria->compare('is_special',$this->is_special);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_id',$this->update_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Scheduleshift the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
