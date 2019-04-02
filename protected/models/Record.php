<?php

/**
 * This is the model class for table "record".
 *
 * The followings are the available columns in table 'record':
 * @property integer $id
 * @property string $mem_num
 * @property string $info_num
 * @property string $reader_num
 * @property string $start_five
 * @property string $end_five
 * @property string $is_record
 * @property string $shiftID
 * @property string $flashDate
 * @property string $flashTime
 * @property string $memol
 * @property string $capfilename
 * @property string $attendance
 * @property string $ctrlmode
 * @property string $doorgroup
 * @property string $timezone
 * @property string $floorgroup
 * @property string $homeID
 * @property string $seriano
 * @property string $name
 * @property string $second
 * @property string $doorstatus
 * @property string $departmentNo
 * @property string $DayNightClass
 * @property string $PlateNo
 * @property string $Temperature
 * @property string $ClientNo
 * @property string $Preserve
 */
class Record extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			/*array('mem_num, info_num, reader_num, start_five, end_five, is_record, shiftID, flashDate, flashTime, memol, capfilename, attendance, ctrlmode, doorgroup, timezone, floorgroup, homeID, seriano, name, second, doorstatus, departmentNo, DayNightClass, PlateNo, Temperature, ClientNo, Preserve', 'required'),*/
			array('mem_num, homeID, PlateNo', 'length', 'max'=>10),
			array('info_num, departmentNo, ClientNo', 'length', 'max'=>2),
			array('reader_num, attendance, doorgroup, timezone', 'length', 'max'=>3),
			array('start_five, end_five, floorgroup, seriano, Temperature', 'length', 'max'=>5),
			array('is_record, shiftID, ctrlmode, second, doorstatus, DayNightClass', 'length', 'max'=>1),
			array('flashTime, Preserve', 'length', 'max'=>8),
			array('memol', 'length', 'max'=>40),
			array('capfilename', 'length', 'max'=>25),
			array('name', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mem_num, info_num, reader_num, start_five, end_five, is_record, shiftID, flashDate, flashTime, memol, capfilename, attendance, ctrlmode, doorgroup, timezone, floorgroup, homeID, seriano, name, second, doorstatus, departmentNo, DayNightClass, PlateNo, Temperature, ClientNo, Preserve', 'safe', 'on'=>'search'),
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
			'mem_num' => 'Mem Num',
			'info_num' => 'Info Num',
			'reader_num' => 'Reader Num',
			'start_five' => 'Start Five',
			'end_five' => 'End Five',
			'is_record' => 'Is Record',
			'shiftID' => 'Shift',
			'flashDate' => 'Flash Date',
			'flashTime' => 'Flash Time',
			'memol' => 'Memol',
			'capfilename' => 'Capfilename',
			'attendance' => 'Attendance',
			'ctrlmode' => 'Ctrlmode',
			'doorgroup' => 'Doorgroup',
			'timezone' => 'Timezone',
			'floorgroup' => 'Floorgroup',
			'homeID' => 'Home',
			'seriano' => 'Seriano',
			'name' => 'Name',
			'second' => 'Second',
			'doorstatus' => 'Doorstatus',
			'departmentNo' => 'Department No',
			'DayNightClass' => 'Day Night Class',
			'PlateNo' => 'Plate No',
			'Temperature' => 'Temperature',
			'ClientNo' => 'Client No',
			'Preserve' => 'Preserve',
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
		$criteria->compare('mem_num',$this->mem_num,true);
		$criteria->compare('info_num',$this->info_num,true);
		$criteria->compare('reader_num',$this->reader_num,true);
		$criteria->compare('start_five',$this->start_five,true);
		$criteria->compare('end_five',$this->end_five,true);
		$criteria->compare('is_record',$this->is_record,true);
		$criteria->compare('shiftID',$this->shiftID,true);
		$criteria->compare('flashDate',$this->flashDate,true);
		$criteria->compare('flashTime',$this->flashTime,true);
		$criteria->compare('memol',$this->memol,true);
		$criteria->compare('capfilename',$this->capfilename,true);
		$criteria->compare('attendance',$this->attendance,true);
		$criteria->compare('ctrlmode',$this->ctrlmode,true);
		$criteria->compare('doorgroup',$this->doorgroup,true);
		$criteria->compare('timezone',$this->timezone,true);
		$criteria->compare('floorgroup',$this->floorgroup,true);
		$criteria->compare('homeID',$this->homeID,true);
		$criteria->compare('seriano',$this->seriano,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('second',$this->second,true);
		$criteria->compare('doorstatus',$this->doorstatus,true);
		$criteria->compare('departmentNo',$this->departmentNo,true);
		$criteria->compare('DayNightClass',$this->DayNightClass,true);
		$criteria->compare('PlateNo',$this->PlateNo,true);
		$criteria->compare('Temperature',$this->Temperature,true);
		$criteria->compare('ClientNo',$this->ClientNo,true);
		$criteria->compare('Preserve',$this->Preserve,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Record the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
