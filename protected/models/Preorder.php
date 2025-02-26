<?php

/**
 * This is the model class for table "preorder".
 *
 * The followings are the available columns in table 'preorder':
 * @property integer $id
 * @property string $name
 * @property integer $sex
 * @property string $phone
 * @property string $mail
 * @property string $address
 * @property string $des
 * @property integer $onum
 * @property integer $status
 * @property string $createdate
 * @property string $editdate
 */
class Preorder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'preorder';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, sex, phone, mail, address, des, onum, status, createdate, editdate', 'required'),
			array('id, sex, onum, status', 'numerical', 'integerOnly'=>true),
			array('name, phone', 'length', 'max'=>20),
			array('mail','length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, sex, phone, mail, address, des, onum, status, createdate, editdate', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'sex' => 'Sex',
			'phone' => 'Phone',
			'mail' => 'Mail',
			'address' => 'Address',
			'des' => 'Des',
			'onum' => 'Onum',
			'status' => 'Status',
			'createdate' => 'Createdate',
			'editdate' => 'Editdate',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('mail',$this->mail,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('des',$this->des,true);
		$criteria->compare('onum',$this->onum);
		$criteria->compare('status',$this->status);
		$criteria->compare('createdate',$this->createdate,true);
		$criteria->compare('editdate',$this->editdate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Preorder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
