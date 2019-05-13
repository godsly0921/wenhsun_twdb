<?php

/**
 * This is the model class for table "coupon".
 *
 * The followings are the available columns in table 'coupon':
 * @property integer $coupon_id
 * @property string $coupon_name
 * @property string $coupon_code
 * @property integer $coupon_pic
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 * @property string $create_time
 * @property integer $create_account_id
 * @property string $update_time
 * @property integer $update_account_id
 */
class Coupon extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'coupon';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('coupon_name, coupon_code, coupon_pic, start_time, end_time, status, create_time, create_account_id', 'required'),
			array('coupon_pic, status, create_account_id, update_account_id', 'numerical', 'integerOnly'=>true),
			array('coupon_name', 'length', 'max'=>100),
			array('coupon_code', 'length', 'max'=>50),
			array('update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('coupon_id, coupon_name, coupon_code, coupon_pic, start_time, end_time, status, create_time, create_account_id, update_time, update_account_id', 'safe', 'on'=>'search'),
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
			'coupon_id' => '流水號',
			'coupon_name' => '優惠活動名稱',
			'coupon_code' => '優惠折扣代號',
			'coupon_pic' => '張數',
			'start_time' => '開始時間',
			'end_time' => '結束時間',
			'status' => '優惠狀態 ( 0：停用 1：啟用 )',
			'create_time' => '建立時間',
			'create_account_id' => '建立人員',
			'update_time' => '更新時間',
			'update_account_id' => '更新人員',
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

		$criteria->compare('coupon_id',$this->coupon_id);
		$criteria->compare('coupon_name',$this->coupon_name,true);
		$criteria->compare('coupon_code',$this->coupon_code,true);
		$criteria->compare('coupon_pic',$this->coupon_pic);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_account_id',$this->create_account_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_account_id',$this->update_account_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Coupon the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
