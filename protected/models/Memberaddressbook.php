<?php

/**
 * This is the model class for table "member_address_book".
 *
 * The followings are the available columns in table 'member_address_book':
 * @property integer $address_book_id
 * @property integer $member_id
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property string $nationality
 * @property string $country
 * @property string $town
 * @property integer $codezip
 * @property string $address
 * @property integer $invoice_category
 * @property integer $invoice_number
 * @property string $invoice_title
 */
class Memberaddressbook extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'member_address_book';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, mobile, invoice_category', 'required'),
			array('member_id, codezip, invoice_category, invoice_number', 'numerical', 'integerOnly'=>true),
			array('name, mobile', 'length', 'max'=>50),
			array('email, country, invoice_title', 'length', 'max'=>100),
			array('nationality', 'length', 'max'=>2),
			array('town', 'length', 'max'=>36),
			array('address', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('address_book_id, member_id, name, mobile, email, nationality, country, town, codezip, address, invoice_category, invoice_number, invoice_title', 'safe', 'on'=>'search'),
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
			'address_book_id' => '通訊錄 ID',
			'member_id' => '會員編號',
			'name' => '姓名',
			'mobile' => '電話',
			'email' => '電子郵件',
			'nationality' => '國籍',
			'country' => '縣市',
			'town' => '鄉鎮',
			'codezip' => '郵遞區號',
			'address' => '地址',
			'invoice_category' => '發票類型 ( 0：捐贈 1：二聯 2：三聯 )',
			'invoice_number' => '發票號碼',
			'invoice_title' => '發票抬頭',
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

		$criteria->compare('address_book_id',$this->address_book_id);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('nationality',$this->nationality,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('town',$this->town,true);
		$criteria->compare('codezip',$this->codezip);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('invoice_category',$this->invoice_category);
		$criteria->compare('invoice_number',$this->invoice_number);
		$criteria->compare('invoice_title',$this->invoice_title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Memberaddressbook the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
