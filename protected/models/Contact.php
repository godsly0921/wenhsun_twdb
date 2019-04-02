<?php

/**
 * This is the model class for table "contact".
 *
 * The followings are the available columns in table 'contact':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $detail
 * @property string $createtime
 * @property integer $type
 */
class Contact extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact';
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
                'name, gender, phone, address',
                'required',
                'message' =>'請輸入'. '{attribute}'
            ),
			array('type', 'numerical', 'integerOnly'=>true),
			array('phone', 'numerical', 'integerOnly'=>true),
			array('name, phone', 'length', 'max'=>32),
			//array('email', 'length', 'max'=>256),
			//array('email','email'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, email, gender,address, createtime, type', 'safe', 'on'=>'search'),
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
//		return array(
//			'id' => 'ID',
//			'name' => Yii::t('messages', 'contactForm')['name'],
//            'phone' => Yii::t('messages', 'contactForm')['phone'],
//            'gender' => Yii::t('messages', 'contactForm')['gender'],
//			'address' => Yii::t('messages', 'contactForm')['address'],
//			'createtime' => 'Createtime',
//			'type' => 'Type',
//		);
		return array(
			'id' => 'ID',
			'name' => 'name',
            'phone' => 'phone',
            'gender' => 'gender',
			'address' => 'address',
			'createtime' => 'Createtime',
			'type' => 'Type',
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
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Contact the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
