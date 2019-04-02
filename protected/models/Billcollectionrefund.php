<?php

/**
 * This is the model class for table "bill_collection_refund".
 *
 * The followings are the available columns in table 'bill_collection_refund':
 * @property integer $bill_collection_refund_id
 * @property integer $member_id
 * @property integer $collection_refund_amount
 * @property integer $collection_or_refund
 * @property integer $collection_refund_type
 * @property integer $handman_member_id
 * @property integer $handman_member_type
 * @property string $createtime
 * @property string $memo
 * @property integer $checkout
 * @property integer $bill_record_id
 */
class Billcollectionrefund extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bill_collection_refund';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, collection_refund_amount, handman_member_id, handman_member_type, createtime, memo', 'required'),
			array('member_id, collection_refund_amount, collection_or_refund, collection_refund_type, handman_member_id, handman_member_type, checkout, bill_record_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bill_collection_refund_id, member_id, collection_refund_amount, collection_or_refund, collection_refund_type, handman_member_id, handman_member_type, createtime, memo, checkout, bill_record_id', 'safe', 'on'=>'search'),
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
			'bill_collection_refund_id' => 'Bill Collection Refund',
			'member_id' => '使用者編號',
			'collection_refund_amount' => '收退款金額',
			'collection_or_refund' => '收退款屬性(0：退款；1：收款)',
			'collection_refund_type' => '收退款方式(1：現金；2：轉帳；3：其他)',
			'handman_member_id' => '經手人編號',
			'handman_member_type' => '經手人身份(0：管理者 1：使用者)',
			'createtime' => 'Createtime',
			'memo' => '備註',
			'checkout' => '是否結帳(0：否；1：是)	',
			'bill_record_id' => '每期帳單記錄PK',
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

		$criteria->compare('bill_collection_refund_id',$this->bill_collection_refund_id);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('collection_refund_amount',$this->collection_refund_amount);
		$criteria->compare('collection_or_refund',$this->collection_or_refund);
		$criteria->compare('collection_refund_type',$this->collection_refund_type);
		$criteria->compare('handman_member_id',$this->handman_member_id);
		$criteria->compare('handman_member_type',$this->handman_member_type);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('checkout',$this->checkout);
		$criteria->compare('bill_record_id',$this->bill_record_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Billcollectionrefund the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
