<?php

/**
 * This is the model class for table "member".
 *
 * The followings are the available columns in table 'member':
 * @property integer $id
 * @property string $account
 * @property string $password
 * @property string $name
 * @property integer $sex
 * @property integer $age
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property integer $status
 * @property string $fb
 * @property string $google
 * @property integer $official
 * @property string $create_date
 * @property string $edit_date
 * @property string $auth_code
 * @property string $level
 * @property string $lang
 * @property string $resolution
 * @property integer $info
 * @property string $year
 * @property string $month
 * @property string $day
 * @property string $region
 * @property string $inter_tel_id
 * @property integer $count
 */
class Umember extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// 必填的欄位
			//array('account, password, name, sex, age, phone, email, address, ', 'required'),
			//array('email', 'required'),
			// 帳號不重複
			array('account','unique'),
			
			array('age, status, official, info, count', 'numerical', 'integerOnly'=>true),
			
			// 只可輸入bool值
			array('sex','boolean'),
            
            // 限制帳號長度
			array('account', 'length', 'min'=>6,'max'=>12),
			array('password', 'length','min'=>8,'max'=>64),
			array('name, lang', 'length', 'max'=>20),
			
			// 限制手機長度
			array('phone', 'length', 'min'=>10,'max'=>16),
			array('email', 'length', 'max'=>255),
			// 信箱格式
            array('email', 'email'),
            array('info','boolean'),
            array('lang', 'in','range' => array('eng', 'jp', 'zh_tw')),
			array('auth_code, level', 'length', 'max'=>10),
			array('resolution', 'length', 'max'=>5),
			array('resolution', 'in','range' => array('480', '720', '1080')),
			array('year', 'length', 'max'=>4),
			array('month, day', 'length', 'max'=>2),
			array('inter_tel_id', 'length', 'max'=>6),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, account, password, name, sex, age, phone, email, address, status, fb, google, official, create_date, edit_date, auth_code, level, lang, resolution, info, year, month, day, region, inter_tel_id, count,ios_iap_receipt_result,ios_iap_receipt,token', 'safe', 'on'=>'search'),
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
			'account' => 'Account',
			'password' => 'Password',
			'name' => 'Name',
			'sex' => 'Sex',
			'age' => 'Age',
			'phone' => 'Phone',
			'email' => 'Email',
			'address' => 'Address',
			'status' => 'Status',
			'fb' => 'Fb',
			'google' => 'Google',
			'official' => 'Official',
			'create_date' => 'Create Date',
			'edit_date' => 'Edit Date',
			'auth_code' => 'Auth Code',
			'level' => 'Level',
			'lang' => 'Lang',
			'resolution' => 'Resolution',
			'info' => 'Info',
			'year' => 'Year',
			'month' => 'Month',
			'day' => 'Day',
			'region' => 'Region',
			'inter_tel_id' => 'Inter Tel',
			'count' => 'Count',
			'ios_iap_receipt' => 'IOS_iap_receipt',
			'ios_iap_receipt_result' => 'IOS_iap_receipt_result',
			'token' => 'token',
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
		$criteria->compare('account',$this->account,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('age',$this->age);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('fb',$this->fb,true);
		$criteria->compare('google',$this->google,true);
		$criteria->compare('official',$this->official);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('edit_date',$this->edit_date,true);
		$criteria->compare('auth_code',$this->auth_code,true);
		$criteria->compare('level',$this->level,true);
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('resolution',$this->resolution,true);
		$criteria->compare('info',$this->info);
		$criteria->compare('year',$this->year,true);
		$criteria->compare('month',$this->month,true);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('region',$this->region,true);
		$criteria->compare('inter_tel_id',$this->inter_tel_id,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('ios_iap_receipt',$this->ios_iap_receipt,true);
		$criteria->compare('ios_iap_receipt_result',$this->ios_iap_receipt_result,true);
		$criteria->compare('token',$this->token,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Umember the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
