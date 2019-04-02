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
 * @property string $phone1
 * @property string $phone2
 * @property string $email1
 * @property string $email2
 * @property string $address
 * @property integer $status
 * @property string $create_date
 * @property string $edit_date
 * @property string $user_group
 * @property string $year
 * @property string $month
 * @property string $day
 * @property string $tel_no1
 * @property string $tel_no2
 * @property string $card_number
 * @property string $grp_lv1
 * @property string $grp_lv2
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
			array('account, password, name, sex, phone1, email1, address, status, create_date, edit_date, year, month, day, tel_no1, grp_lv1, grp_lv2', 'required'),
			/*array('account, password, name, sex, phone1, email1, , address, status, create_date, edit_date, year, month, day, tel_no1, card_number', 'required'),*/
			array('sex, status', 'numerical', 'integerOnly'=>true),
			array('account', 'length', 'max'=>50),
			array('password', 'length', 'max'=>64),
			array('name, tel_no1, tel_no2, card_number, grp_lv1, grp_lv2', 'length', 'max'=>20),
			array('phone1, phone2', 'length', 'max'=>18),
			array('email1, email2', 'length', 'max'=>255),
			array('user_group', 'length', 'max'=>10),
			array('year', 'length', 'max'=>4),
			array('month, day', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, account, password, name, sex, phone1, phone2, email1, email2, address, status, create_date, edit_date, user_group, year, month, day, tel_no1, tel_no2, card_number, grp_lv1, grp_lv2', 'safe', 'on'=>'search'),
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
			'phone1' => 'Phone1',
			'phone2' => 'Phone2',
			'email1' => 'Email1',
			'email2' => 'Email2',
			'address' => 'Address',
			'status' => 'Status',
			'create_date' => 'Create Date',
			'edit_date' => 'Edit Date',
			'user_group' => 'Level',
			'year' => 'Year',
			'month' => 'Month',
			'day' => 'Day',
			'tel_no1' => 'Tel No1',
			'tel_no2' => 'Tel No2',
			'card_number' => 'Card Number',
			'grp_lv1' => 'Grp Lv1',
			'grp_lv2' => 'Grp Lv2',
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
		$criteria->compare('phone1',$this->phone1,true);
		$criteria->compare('phone2',$this->phone2,true);
		$criteria->compare('email1',$this->email1,true);
		$criteria->compare('email2',$this->email2,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('edit_date',$this->edit_date,true);
		$criteria->compare('user_group',$this->user_group,true);
		$criteria->compare('year',$this->year,true);
		$criteria->compare('month',$this->month,true);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('tel_no1',$this->tel_no1,true);
		$criteria->compare('tel_no2',$this->tel_no2,true);
		$criteria->compare('card_number',$this->card_number,true);
		$criteria->compare('grp_lv1',$this->grp_lv1,true);
		$criteria->compare('grp_lv2',$this->grp_lv2,true);

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
