<?php

/**
 * This is the model class for table "order_message".
 *
 * The followings are the available columns in table 'order_message':
 * @property integer $order_message_id
 * @property string $order_id
 * @property string $message
 * @property string $create_time
 * @property integer $reply_account_id
 */
class Ordermessage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, message, create_time', 'required'),
			array('reply_account_id', 'numerical', 'integerOnly'=>true),
			array('order_id', 'length', 'max'=>14),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('order_message_id, order_id, message, create_time, reply_account_id', 'safe', 'on'=>'search'),
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
			'order_message_id' => '流水號',
			'order_id' => '訂單編號',
			'message' => '留言內容',
			'create_time' => '留言時間',
			'reply_account_id' => '回覆人員 ID',
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

		$criteria->compare('order_message_id',$this->order_message_id);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('reply_account_id',$this->reply_account_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ordermessage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
