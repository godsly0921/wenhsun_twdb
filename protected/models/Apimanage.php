<?php

/**
 * This is the model class for table "api_manage".
 *
 * The followings are the available columns in table 'api_manage':
 * @property integer $id
 * @property string $api_key
 * @property string $api_password
 * @property string $api_token
 * @property string $token_createtime
 * @property string $createtime
 * @property integer $status
 * @property string $remark
 */
class Apimanage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'api_manage';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('api_key, api_password', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('api_key', 'length', 'max'=>64),
			array('api_password', 'length', 'max'=>16),
			array('api_token', 'length', 'max'=>256),
			array('remark', 'length', 'max'=>100),
			array('token_createtime, createtime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, api_key, api_password, api_token, token_createtime, createtime, status, remark', 'safe', 'on'=>'search'),
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
			'api_key' => 'Api Key',
			'api_password' => 'Api Password',
			'api_token' => 'Api Token',
			'token_createtime' => 'Token Createtime',
			'createtime' => 'Createtime',
			'status' => '狀態 ( 0：停用 1：啟用 99：刪除 )',
			'remark' => 'Remark',
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
		$criteria->compare('api_key',$this->api_key,true);
		$criteria->compare('api_password',$this->api_password,true);
		$criteria->compare('api_token',$this->api_token,true);
		$criteria->compare('token_createtime',$this->token_createtime,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('remark',$this->remark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Apimanage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
