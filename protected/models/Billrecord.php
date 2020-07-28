<?php

/**
 * This is the model class for table "bill_record".
 *
 * The followings are the available columns in table 'bill_record':
 * @property integer $bill_record_id
 * @property integer $member_id
 * @property integer $opening_balance
 * @property integer $other_fee
 * @property integer $device_fee
 * @property integer $door_fee
 * @property integer $ending_balance
 * @property integer $collection_refund
 * @property integer $receive_member_id
 * @property integer $receive_member_type
 * @property string $checkout_time
 * @property string $createtime
 */
class Billrecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bill_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, opening_balance, other_fee, device_fee, door_fee, ending_balance, collection_refund, receive_member_id, checkout_time, createtime', 'required'),
			array('member_id, opening_balance, other_fee, device_fee, door_fee, ending_balance, collection_refund, receive_member_id, receive_member_type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bill_record_id, member_id, opening_balance, other_fee, device_fee, door_fee, ending_balance, collection_refund, receive_member_id, receive_member_type, checkout_time, createtime', 'safe', 'on'=>'search'),
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
			'bill_record_id' => 'Bill Record',
			'member_id' => '使用者編號',
			'opening_balance' => '上期餘額(上一筆的本期餘額 + 上一筆的本期收退款金額)',
			'other_fee' => '本期其他費',
			'device_fee' => '本期機台費',
			'door_fee' => '本期門禁費',
			'ending_balance' => '本期餘額(上期餘額-本期其他費-本期機台費-本期門禁費)',
			'collection_refund' => '本期收退款',
			'receive_member_id' => '收款人',
			'receive_member_type' => '收款人身份(0：管理者 1：使用者)	',
			'checkout_time' => '結帳日期',
			'createtime' => '收款時間',
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

		$criteria->compare('bill_record_id',$this->bill_record_id);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('opening_balance',$this->opening_balance);
		$criteria->compare('other_fee',$this->other_fee);
		$criteria->compare('device_fee',$this->device_fee);
		$criteria->compare('door_fee',$this->door_fee);
		$criteria->compare('ending_balance',$this->ending_balance);
		$criteria->compare('collection_refund',$this->collection_refund);
		$criteria->compare('receive_member_id',$this->receive_member_id);
		$criteria->compare('receive_member_type',$this->receive_member_type);
		$criteria->compare('checkout_time',$this->checkout_time,true);
		$criteria->compare('createtime',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Billrecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
