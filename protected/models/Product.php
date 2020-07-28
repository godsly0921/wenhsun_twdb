<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $product_id
 * @property string $product_name
 * @property integer $coupon_id
 * @property double $pic_point
 * @property integer $pic_number
 * @property integer $product_type
 * @property integer $price
 * @property integer $status
 * @property string $create_time
 * @property integer $create_account_id
 * @property string $update_time
 * @property integer $update_account_id
 */
class Product extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_name, coupon_id, pic_point, pic_number, price, create_time, create_account_id', 'required'),
			array('coupon_id, pic_number, product_type, price, status, create_account_id, update_account_id', 'numerical', 'integerOnly'=>true),
			array('pic_point', 'numerical'),
			array('product_name', 'length', 'max'=>100),
			array('update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('product_id, product_name, coupon_id, pic_point, pic_number, product_type, price, status, create_time, create_account_id, update_time, update_account_id', 'safe', 'on'=>'search'),
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
			'product_id' => '流水號',
			'product_name' => '產品名稱',
			'coupon_id' => '優惠折扣ID,FK coupon，若無則填 0',
			'pic_point' => '點數,若為自由載則填 0',
			'pic_number' => '張數,若為點數則填 0',
			'product_type' => '產品類型 ( 1：點數  2：自由載 30 天  3：自由載 90 天  4：自由載 360 天 )',
			'price' => '產品售價',
			'status' => '產品狀態 ( 0：停用 1：啟用 )',
			'create_time' => '上架時間',
			'create_account_id' => '上架人員',
			'update_time' => '更新時間',
			'update_account_id' => '更新人員',
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

		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('coupon_id',$this->coupon_id);
		$criteria->compare('pic_point',$this->pic_point);
		$criteria->compare('pic_number',$this->pic_number);
		$criteria->compare('product_type',$this->product_type);
		$criteria->compare('price',$this->price);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_account_id',$this->create_account_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_account_id',$this->update_account_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
