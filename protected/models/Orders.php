<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table 'orders':
 * @property string $order_id
 * @property integer $member_id
 * @property integer $address_book_id
 * @property string $order_datetime
 * @property integer $pay_type
 * @property integer $order_status
 * @property string $pay_feedback
 * @property string $pya_result
 * @property string $memo
 * @property string $memo_create_time
 * @property integer $memo_create_account_id
 */
class Orders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, member_id, address_book_id, order_datetime, pay_type, order_status', 'required'),
			array('member_id, address_book_id, pay_type, order_status, memo_create_account_id', 'numerical', 'integerOnly'=>true),
			array('order_id', 'length', 'max'=>14),
			array('pay_feedback, pya_result', 'length', 'max'=>500),
			array('memo', 'length', 'max'=>200),
			array('memo_create_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('order_id, member_id, address_book_id, order_datetime, pay_type, order_status, pay_feedback, pya_result, memo, memo_create_time, memo_create_account_id', 'safe', 'on'=>'search'),
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
			'order_id' => '訂單編號',
			'member_id' => '會員編號',
			'address_book_id' => '通訊錄 ID',
			'order_datetime' => '下訂時間',
			'pay_type' => '付款方式 ( 1：信用卡 2：超商繳款 3：超商代碼 4：ATM 轉帳 )',
			'order_status' => '訂單狀態 ( 0：取消訂單 1：未結帳 2：已付款 3：已開通 4：已退款 )',
			'pay_feedback' => '金流 feedback',
			'pya_result' => '金流 result',
			'memo' => '備註',
			'memo_create_time' => '備註建立時間',
			'memo_create_account_id' => '備註建立人員',
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

		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('address_book_id',$this->address_book_id);
		$criteria->compare('order_datetime',$this->order_datetime,true);
		$criteria->compare('pay_type',$this->pay_type);
		$criteria->compare('order_status',$this->order_status);
		$criteria->compare('pay_feedback',$this->pay_feedback,true);
		$criteria->compare('pya_result',$this->pya_result,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('memo_create_time',$this->memo_create_time,true);
		$criteria->compare('memo_create_account_id',$this->memo_create_account_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
