<?php

/**
 * This is the model class for table "img_download".
 *
 * The followings are the available columns in table 'img_download':
 * @property integer $img_download_id
 * @property integer $member_id
 * @property integer $orders_item_id
 * @property integer $download_method
 * @property integer $single_id
 * @property string $size_type
 * @property string $cost
 * @property string $authorization
 * @property string $authorization_no
 * @property string $download_datetime
 */
class Imgdownload extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'img_download';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, orders_item_id, download_method, single_id, size_type, cost, download_datetime', 'required'),
			array('member_id, orders_item_id, download_method, single_id', 'numerical', 'integerOnly'=>true),
			array('size_type', 'length', 'max'=>20),
			array('cost', 'length', 'max'=>4),
			array('authorization', 'length', 'max'=>100),
			array('authorization_no', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('img_download_id, member_id, orders_item_id, download_method, single_id, size_type, cost, authorization, authorization_no, download_datetime', 'safe', 'on'=>'search'),
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
			'img_download_id' => '流水號',
			'member_id' => '會員編號',
			'orders_item_id' => '訂單訂購項目 PK',
			'download_method' => '下載方式 ( 1：點數 2：月方案 )',
			'single_id' => '圖片編號',
			'size_type' => '尺寸類型',
			'cost' => '花費點數/張數',
			'authorization' => '授權對象',
			'authorization_no' => '授權號碼',
			'download_datetime' => '下載時間',
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

		$criteria->compare('img_download_id',$this->img_download_id);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('orders_item_id',$this->orders_item_id);
		$criteria->compare('download_method',$this->download_method);
		$criteria->compare('single_id',$this->single_id);
		$criteria->compare('size_type',$this->size_type,true);
		$criteria->compare('cost',$this->cost,true);
		$criteria->compare('authorization',$this->authorization,true);
		$criteria->compare('authorization_no',$this->authorization_no,true);
		$criteria->compare('download_datetime',$this->download_datetime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Imgdownload the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
