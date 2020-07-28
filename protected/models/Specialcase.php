<?php

/**
 * This is the model class for table "special_case".
 *
 * The followings are the available columns in table 'special_case':
 * @property integer $id
 * @property string $title
 * @property integer $member_id
 * @property string $application_time
 * @property integer $category
 * @property integer $approval_status
 * @property string $approval_time
 * @property integer $approval_account_id
 * @property string $member_ip
 * @property string $msg
 */
class Specialcase extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'special_case';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, member_id, application_time, category', 'required'),
			array('member_id, category, approval_status, approval_account_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>18),
			array('member_ip', 'length', 'max'=>64),
			array('msg', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, member_id, application_time, category, approval_status, approval_time, approval_account_id, member_ip, msg', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'member_id' => 'Member',
			'application_time' => 'Application Time',
			'category' => 'Category',
			'approval_status' => 'Approval Status',
			'approval_time' => 'Approval Time',
			'approval_account_id' => 'Approval Account',
			'member_ip' => 'Member Ip',
			'msg' => 'Msg',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('application_time',$this->application_time,true);
		$criteria->compare('category',$this->category);
		$criteria->compare('approval_status',$this->approval_status);
		$criteria->compare('approval_time',$this->approval_time,true);
		$criteria->compare('approval_account_id',$this->approval_account_id);
		$criteria->compare('member_ip',$this->member_ip,true);
		$criteria->compare('msg',$this->msg,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Specialcase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
