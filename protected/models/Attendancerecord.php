<?php

/**
 * This is the model class for table "attendance_record".
 *
 * The followings are the available columns in table 'attendance_record':
 * @property string $id
 * @property string $employee_id
 * @property string $day
 * @property string $first_time
 * @property string $last_time
 * @property integer $abnormal_type
 * @property string $abnormal
 * @property integer $take
 * @property string $leave_time
 * @property integer $leave_minutes
 * @property string $reply_description
 * @property string $reply_update_at
 * @property string $create_at
 * @property string $update_at
 * @property string $status
 * @property string $start_time
 * @property string $end_time
 * @property string $reason
 * @property string $remark
 * @property string $agent
 * @property string $manager
 */
class Attendancerecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'attendance_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, day, abnormal_type, reply_update_at, create_at, update_at', 'required'),
			array('abnormal_type, take, leave_minutes', 'numerical', 'integerOnly'=>true),
			array('employee_id, agent, manager', 'length', 'max'=>12),
			array('first_time, last_time, abnormal', 'length', 'max'=>64),
			array('reply_description', 'length', 'max'=>128),
			array('status', 'length', 'max'=>1),
			array('reason, remark', 'length', 'max'=>255),
			array('leave_time, start_time, end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employee_id, day, first_time, last_time, abnormal_type, abnormal, take, leave_time, leave_minutes, reply_description, reply_update_at, create_at, update_at, status, start_time, end_time, reason, remark, agent, manager', 'safe', 'on'=>'search'),
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
			'employee_id' => 'Employee',
			'day' => 'Day',
			'first_time' => 'First Time',
			'last_time' => 'Last Time',
			'abnormal_type' => 'Abnormal Type',
			'abnormal' => 'Abnormal',
			'take' => 'Take',
			'leave_time' => 'Leave Time',
			'leave_minutes' => 'Leave Minutes',
			'reply_description' => 'Reply Description',
			'reply_update_at' => 'Reply Update At',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'status' => 'Status',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'reason' => 'Reason',
			'remark' => 'Remark',
			'agent' => 'Agent',
			'manager' => 'Manager',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('employee_id',$this->employee_id,true);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('first_time',$this->first_time,true);
		$criteria->compare('last_time',$this->last_time,true);
		$criteria->compare('abnormal_type',$this->abnormal_type);
		$criteria->compare('abnormal',$this->abnormal,true);
		$criteria->compare('take',$this->take);
		$criteria->compare('leave_time',$this->leave_time,true);
		$criteria->compare('leave_minutes',$this->leave_minutes);
		$criteria->compare('reply_description',$this->reply_description,true);
		$criteria->compare('reply_update_at',$this->reply_update_at,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('agent',$this->agent,true);
		$criteria->compare('manager',$this->manager,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Attendancerecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
