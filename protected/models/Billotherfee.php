<?php

/**
 * This is the model class for table "bill_other_fee".
 *
 * The followings are the available columns in table 'bill_other_fee':
 * @property integer $bill_other_fee_id
 * @property integer $member_id
 * @property integer $fee_amount
 * @property string $fee_create_time
 * @property string $createtime
 * @property integer $checkout
 * @property integer $bill_record_id
 * @property integer $create_member_id
 * @property integer $create_member_type
 * @property string $memo
 */
class Billotherfee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bill_other_fee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, fee_amount, fee_create_time, createtime, checkout, create_member_id, create_member_type, memo', 'required'),
			array('member_id, fee_amount, checkout, bill_record_id, create_member_id, create_member_type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bill_other_fee_id, member_id, fee_amount, fee_create_time, createtime, checkout, bill_record_id, create_member_id, create_member_type, memo', 'safe', 'on'=>'search'),
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
			'bill_other_fee_id' => 'Bill Other Fee',
			'member_id' => '使用者編號',
			'fee_amount' => '費用金額',
			'fee_create_time' => '費用產生日期',
			'createtime' => '記錄時間',
			'checkout' => '是否結帳(0：否；1：是)',
			'bill_record_id' => '每期帳單記錄PK',
			'create_member_id' => '記錄建立者',
			'create_member_type' => '記錄建立者身份',
			'memo' => '備註',
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

		$criteria->compare('bill_other_fee_id',$this->bill_other_fee_id);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('fee_amount',$this->fee_amount);
		$criteria->compare('fee_create_time',$this->fee_create_time,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('checkout',$this->checkout);
		$criteria->compare('bill_record_id',$this->bill_record_id);
		$criteria->compare('create_member_id',$this->create_member_id);
		$criteria->compare('create_member_type',$this->create_member_type);
		$criteria->compare('memo',$this->memo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Billotherfee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
