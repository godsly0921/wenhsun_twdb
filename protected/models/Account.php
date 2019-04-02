<?php

/**
 * This is the model class for table "account".
 *
 * The followings are the available columns in table 'account':
 * @property integer $id
 * @property string $user_account
 * @property string $password
 * @property string $account_name
 * @property integer $account_group
 * @property string $make_time
 * @property integer $account_type
 */
class Account extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(
                'user_account, password, account_name, account_group',
                'required',
                'message' => '請輸入{attribute}'
            ),
			array('account_group, account_type', 'numerical', 'integerOnly'=>true),
			array('user_account, account_name', 'length', 'max'=>28),
			array('password', 'length', 'max'=>128),
			array('make_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_account, password, account_name, account_group, make_time, account_type', 'safe', 'on'=>'search'),
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
			'user_account' => '帳號',
			'password' => '密碼',
			'account_name' => '姓名',
			'account_group' => '群組',
			'make_time' => '建立時間',
			'account_type' => '狀態',
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
		$criteria->compare('user_account',$this->user_account,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('account_name',$this->account_name,true);
		$criteria->compare('account_group',$this->account_group);
		$criteria->compare('make_time',$this->make_time,true);
		$criteria->compare('account_type',$this->account_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Account the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
