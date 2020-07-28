<?php

/**
 * This is the model class for table "member_plan".
 *
 * The followings are the available columns in table 'member_plan':
 * @property integer $member_plan_id
 * @property integer $member_id
 * @property integer $order_item_id
 * @property string $date_start
 * @property string $date_end
 * @property integer $amount
 * @property integer $remain_amount
 * @property integer $status
 */
class Memberplan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'member_plan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, order_item_id, amount, remain_amount, status', 'required'),
			array('member_id, order_item_id, amount, remain_amount, status', 'numerical', 'integerOnly'=>true),
			array('date_start, date_end', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('member_plan_id, member_id, order_item_id, date_start, date_end, amount, remain_amount, status', 'safe', 'on'=>'search'),
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
			'member_plan_id' => '流水號',
			'member_id' => '會員編號	',
			'order_item_id' => '訂單訂購項目 PK',
			'date_start' => '方案開始時間',
			'date_end' => '方案結束時間',
			'amount' => '下載額度',
			'remain_amount' => '剩餘額度',
			'status' => '方案狀態 ( 0：未啟用 1：使用中 2：已結束 )',
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

		$criteria->compare('member_plan_id',$this->member_plan_id);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('order_item_id',$this->order_item_id);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('date_end',$this->date_end,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('remain_amount',$this->remain_amount);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Memberplan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
