<?php

/**
 * This is the model class for table "attendance".
 *
 * The followings are the available columns in table 'attendance':
 * @property string $id
 * @property string $day
 * @property integer $type
 * @property string $description
 * @property string $create_at
 * @property string $update_at
 */
class Attendance extends CActiveRecord
{
    public const SICK_LEAVE = '1'; //普通傷病假
    public const PERSONAL_LEAVE = '2'; //事假
    public const PUBLIC_AFFAIRS_LEAVE = '3'; //公假
    public const OCCUPATIONAL_SICKNESS_LEAVE = '4'; //公傷病假
    public const ANNUAL_LEAVE = '5'; //特別休假
    public const MATERNITY_LEAVE = '6'; //分娩假含例假日
    public const MARITAL_LEAVE = '7'; //婚假
    public const FUNERAL_LEAVE = '8'; //喪假
    public const COMPENSATORY_LEAVE = '9'; //補休假
    public const MENSTRUAL_LEAVE = '10'; //生理假
    public const OVERTIME = '11'; //加班
    public const PATERNITY_LEAVE = '16'; //陪產假
    public const MISCARRIAGE_LEAVE = '17'; //流產假
    public const PRENATAL_LEAVE = '18'; //產檢假

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'attendance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('day, type, description, create_at, update_at', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('day', 'length', 'max'=>19),
			array('description', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, day, type, description, create_at, update_at', 'safe', 'on'=>'search'),
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
			'day' => 'Day',
			'type' => 'Type',
			'description' => 'Description',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
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
		$criteria->compare('day',$this->day,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Attendance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
