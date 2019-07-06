<?php

/**
 * This is the model class for table "schedule".
 *
 * The followings are the available columns in table 'schedule':
 * @property integer $id
 * @property string $empolyee_id
 * @property integer $store_id
 * @property integer $in_out
 * @property string $class
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 * @property string $remark
 * @property string $builder
 * @property integer $builder_type
 * @property integer $canceler
 * @property string $create_time
 * @property string $modify_time
 * @property integer $tobill
 * @property integer $canceler_type
 */
class Schedule extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'schedule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('empolyee_id, store_id, in_out, class, start_time, end_time, status, builder, builder_type, create_time, modify_time', 'required'),
			array('store_id, in_out, status, builder_type, canceler, tobill, canceler_type', 'numerical', 'integerOnly'=>true),
			array('empolyee_id, builder', 'length', 'max'=>32),
			array('class', 'length', 'max'=>2),
			array('remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, empolyee_id, store_id, in_out, class, start_time, end_time, status, remark, builder, builder_type, canceler, create_time, modify_time, tobill, canceler_type', 'safe', 'on'=>'search'),
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
			'empolyee_id' => '員工ID',
			'store_id' => '館別 1:一般館舍 2:茶館',
			'in_out' => '場別 1:內場 2:外場 0:不分',
			'class' => '班別 A、B',
			'start_time' => '排班開始時間',
			'end_time' => '排班結束時間',
			'status' => '排班是否正常使用 0:排班未使用 1:排班已使用 3:排班取消',
			'remark' => '備註',
			'builder' => '預約者',
			'builder_type' => 'Builder Type',
			'canceler' => '取消者',
			'create_time' => '建立時間',
			'modify_time' => '異動時間',
			'tobill' => 'Tobill',
			'canceler_type' => '0：管理者 1：使用者',
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
		$criteria->compare('empolyee_id',$this->empolyee_id,true);
		$criteria->compare('store_id',$this->store_id);
		$criteria->compare('in_out',$this->in_out);
		$criteria->compare('class',$this->class,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('builder',$this->builder,true);
		$criteria->compare('builder_type',$this->builder_type);
		$criteria->compare('canceler',$this->canceler);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('modify_time',$this->modify_time,true);
		$criteria->compare('tobill',$this->tobill);
		$criteria->compare('canceler_type',$this->canceler_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Schedule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
