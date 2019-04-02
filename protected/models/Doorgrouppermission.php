<?php

/**
 * This is the model class for table "door_group_permission".
 *
 * The followings are the available columns in table 'door_group_permission':
 * @property integer $id
 * @property integer $door_group_permission_number
 * @property string $door_group_permission_name
 * @property string $door_group_permission_list
 */
class Doorgrouppermission extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'door_group_permission';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(
                'door_group_permission_number, door_group_permission_name',
                'required',
                'message' => '請輸入{attribute}'
            ),
            array('door_group_permission_list', 'required', 'message' => '請勾選{attribute}'),
			array('door_group_permission_number', 'numerical', 'integerOnly' => true, 'min' => 0, 'max' => 255),
			array('door_group_permission_name', 'length', 'max' => 20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, door_group_permission_number, door_group_permission_name, door_group_permission_list', 'safe', 'on'=>'search'),
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
			'id' => 'id',
			'door_group_permission_number' => '群組權重',
			'door_group_permission_name' => '群組名稱',
			'door_group_permission_list' => '權限',
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
		$criteria->compare('door_group_permission_number',$this->door_group_permission_number);
		$criteria->compare('door_group_permission_name',$this->door_group_permission_name,true);
		$criteria->compare('door_group_permission_list',$this->door_group_permission_list,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Doordoor_group_permissionmission the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
