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
			array('account, password, name, email', 'required')
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	// /**
	//  * @return array customized attribute labels (name=>label)
	//  */
	// public function attributeLabels()
	// {
	// 	return array(
	// 		'id' => 'ID',
	// 		'account' => 'Account',
	// 		'password' => 'Password',
	// 		'name' => 'Name',
	// 		'sex' => 'Sex',
	// 		'phone1' => 'Phone1',
	// 		'phone2' => 'Phone2',
	// 		'email1' => 'Email1',
	// 		'email2' => 'Email2',
	// 		'address' => 'Address',
	// 		'status' => 'Status',
	// 		'create_date' => 'Create Date',
	// 		'edit_date' => 'Edit Date',
	// 		'user_group' => 'Level',
	// 		'year' => 'Year',
	// 		'month' => 'Month',
	// 		'day' => 'Day',
	// 		'tel_no1' => 'Tel No1',
	// 		'tel_no2' => 'Tel No2',
	// 		'card_number' => 'Card Number',
	// 		'grp_lv1' => 'Grp Lv1',
	// 		'grp_lv2' => 'Grp Lv2',
	// 	);
	// }

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

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('account', $this->account, true);
		$criteria->compare('password', $this->password, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('email', $this->email);
		$criteria->compare('gender', $this->gender);
		$criteria->compare('birthday', $this->birthday);
		$criteria->compare('birthday', $this->phone);
		$criteria->compare('birthday', $this->mobile);
		$criteria->compare('member_type', $this->member_type);
		$criteria->compare('account_type', $this->account_type);
		$criteria->compare('active', $this->active);
		$criteria->compare('nationality', $this->nationality);
		$criteria->compare('county', $this->county);
		$criteria->compare('town', $this->town);
		$criteria->compare('address', $this->address);
		$criteria->compare('active_point', $this->active_point);
		$criteria->compare('inactive_point', $this->intactive_point);
		$criteria->compare('create_date', $this->create_date);
		$criteria->compare('create_date', $this->update_date);
		$criteria->compare('create_by', $this->create_by);
		$criteria->compare('update_by', $this->update_by);


		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Member the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
