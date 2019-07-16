<?php

/**
 * This is the model class for table "member".
 *
 * The followings are the available columns in table 'member':
 * @property integer $id
 * @property string $stop_card_remark
 * @property integer $stop_card_people
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
 * @property string $invalidation_date
 * @property string $create_date
 * @property string $edit_date
 * @property integer $user_group
 * @property string $year
 * @property string $month
 * @property string $day
 * @property string $tel_no1
 * @property string $tel_no2
 * @property string $card_number
 * @property string $grp_lv1
 * @property string $grp_lv2
 * @property integer $professor
 * @property string $stop_card_datetime
 * @property string $device_permission
 * @property string $device_permission_type
 * @property string $door
 * @property string $time
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
			array('stop_card_remark, stop_card_people, account, password, name, sex, phone1, phone2, email1, email2, address, status, create_date, edit_date, user_group, year, month, day, tel_no1, tel_no2, card_number, grp_lv1, grp_lv2, professor', 'required'),
			array('stop_card_people, sex, status, user_group, professor', 'numerical', 'integerOnly'=>true),
			array('stop_card_remark, device_permission', 'length', 'max'=>256),
			array('account', 'length', 'max'=>50),
			array('password', 'length', 'max'=>64),
			array('name, tel_no1, tel_no2, card_number, grp_lv1, grp_lv2', 'length', 'max'=>20),
			array('phone1, phone2', 'length', 'max'=>18),
			array('email1, email2', 'length', 'max'=>255),
			array('year', 'length', 'max'=>4),
			array('month, day, time', 'length', 'max'=>2),
			array('door', 'length', 'max'=>3),
			array('invalidation_date, stop_card_datetime, device_permission_type', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, stop_card_remark, stop_card_people, account, password, name, sex, phone1, phone2, email1, email2, address, status, invalidation_date, create_date, edit_date, user_group, year, month, day, tel_no1, tel_no2, card_number, grp_lv1, grp_lv2, professor, stop_card_datetime, device_permission, device_permission_type, door, time', 'safe', 'on'=>'search'),
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
			'id' => '會員流水編號',
			'stop_card_remark' => '備註原因',
			'stop_card_people' => '復卡人',
			'account' => '會員帳號',
			'password' => '會員密碼',
			'name' => '會員姓名',
			'sex' => '性別',
			'phone1' => '年齡',
			'phone2' => '手機',
			'email1' => '信箱',
			'email2' => 'Email2',
			'address' => '地址',
			'status' => '狀態',
			'invalidation_date' => '作廢日期(無效日期)',
			'create_date' => '註冊時間',
			'edit_date' => '異動時間',
			'user_group' => '角色',
			'year' => '生日年',
			'month' => '生日月',
			'day' => '生日日',
			'tel_no1' => '電話',
			'tel_no2' => 'Tel No2',
			'card_number' => 'Card Number',
			'grp_lv1' => '第一層分類',
			'grp_lv2' => '分類第二層',
			'professor' => '指導教授',
			'stop_card_datetime' => '停卡日期',
			'device_permission' => '使用者儀器權限，這邊存放json供解析',
			'device_permission_type' => '機台權限',
			'door' => '最多255',
			'time' => '最多64',
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
		$criteria->compare('stop_card_remark',$this->stop_card_remark,true);
		$criteria->compare('stop_card_people',$this->stop_card_people);
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
		$criteria->compare('invalidation_date',$this->invalidation_date,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('edit_date',$this->edit_date,true);
		$criteria->compare('user_group',$this->user_group);
		$criteria->compare('year',$this->year,true);
		$criteria->compare('month',$this->month,true);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('tel_no1',$this->tel_no1,true);
		$criteria->compare('tel_no2',$this->tel_no2,true);
		$criteria->compare('card_number',$this->card_number,true);
		$criteria->compare('grp_lv1',$this->grp_lv1,true);
		$criteria->compare('grp_lv2',$this->grp_lv2,true);
		$criteria->compare('professor',$this->professor);
		$criteria->compare('stop_card_datetime',$this->stop_card_datetime,true);
		$criteria->compare('device_permission',$this->device_permission,true);
		$criteria->compare('device_permission_type',$this->device_permission_type,true);
		$criteria->compare('door',$this->door,true);
		$criteria->compare('time',$this->time,true);

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
