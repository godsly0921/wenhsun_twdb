<?php

/**
 * This is the model class for table "member".
 *
 * The followings are the available columns in table 'member':
 * @property integer $id
 * @property string $account
 * @property string $password
 * @property string $google_sub
 * @property string $google_locale
 * @property string $fb_user_id
 * @property string $name
 * @property string $email
 * @property string $gender
 * @property string $birthday
 * @property string $phone
 * @property string $mobile
 * @property string $member_type
 * @property string $account_type
 * @property string $active
 * @property string $verification_code
 * @property string $nationality
 * @property string $county
 * @property string $town
 * @property string $address
 * @property double $active_point
 * @property double $inactive_point
 * @property string $create_date
 * @property string $update_date
 * @property integer $create_by
 * @property string $update_by
 */
class Member extends CActiveRecord
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
			array('account, name, email, account_type, active, create_date, update_date', 'required'),
			array('create_by', 'numerical', 'integerOnly'=>true),
			array('active_point, inactive_point', 'numerical'),
			array('account', 'length', 'max'=>64),
			array('password, email', 'length', 'max'=>128),
			array('google_sub, fb_user_id, address', 'length', 'max'=>256),
			array('google_locale', 'length', 'max'=>50),
			array('name', 'length', 'max'=>32),
			array('gender, active', 'length', 'max'=>1),
			array('phone, mobile', 'length', 'max'=>16),
			array('member_type', 'length', 'max'=>10),
			array('account_type', 'length', 'max'=>12),
			array('verification_code', 'length', 'max'=>20),
			array('nationality', 'length', 'max'=>2),
			array('county, town', 'length', 'max'=>36),
			array('update_by', 'length', 'max'=>45),
			array('birthday', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, account, password, google_sub, google_locale, fb_user_id, name, email, gender, birthday, phone, mobile, member_type, account_type, active, verification_code, nationality, county, town, address, active_point, inactive_point, create_date, update_date, create_by, update_by', 'safe', 'on'=>'search'),
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
			'account' => '帳號',
			'password' => '密碼',
			'google_sub' => 'google帳號id',
			'google_locale' => 'google帳號語言',
			'fb_user_id' => 'fb帳號id',
			'name' => '姓名',
			'email' => 'Email',
			'gender' => '性別',
			'birthday' => '生日',
			'phone' => '電話',
			'mobile' => '手機',
			'member_type' => '會員類型/VIP',
			'account_type' => '會員來源 (1:後台建立 2:前台註冊 3:google 帳號 4: fb 帳號 )',
			'active' => 'Active',
			'verification_code' => 'Verification Code',
			'nationality' => '國籍',
			'county' => '縣市',
			'town' => '鄉鎮',
			'address' => '地址',
			'active_point' => '生效點數',
			'inactive_point' => '失效點數',
			'create_date' => 'Create Date',
			'update_date' => 'Update Date',
			'create_by' => 'Create By',
			'update_by' => 'Update By',
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
		$criteria->compare('google_sub',$this->google_sub,true);
		$criteria->compare('google_locale',$this->google_locale,true);
		$criteria->compare('fb_user_id',$this->fb_user_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('member_type',$this->member_type,true);
		$criteria->compare('account_type',$this->account_type,true);
		$criteria->compare('active',$this->active,true);
		$criteria->compare('verification_code',$this->verification_code,true);
		$criteria->compare('nationality',$this->nationality,true);
		$criteria->compare('county',$this->county,true);
		$criteria->compare('town',$this->town,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('active_point',$this->active_point);
		$criteria->compare('inactive_point',$this->inactive_point);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('update_date',$this->update_date,true);
		$criteria->compare('create_by',$this->create_by);
		$criteria->compare('update_by',$this->update_by,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Member the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
