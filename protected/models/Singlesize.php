<?php

/**
 * This is the model class for table "single_size".
 *
 * The followings are the available columns in table 'single_size':
 * @property integer $single_size_id
 * @property integer $single_id
 * @property string $size_type
 * @property string $size_description
 * @property integer $dpi
 * @property string $mp
 * @property string $w_h
 * @property string $print_w_h
 * @property integer $file_size
 * @property string $ext
 * @property integer $sale_twd
 * @property double $sale_point
 */
class Singlesize extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'single_size';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('single_id, size_type, size_description', 'required'),
			array('single_id, dpi, file_size, sale_twd', 'numerical', 'integerOnly'=>true),
			array('sale_point', 'numerical'),
			array('size_type, mp, w_h, print_w_h', 'length', 'max'=>20),
			array('size_description', 'length', 'max'=>30),
			array('ext', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('single_size_id, single_id, size_type, size_description, dpi, mp, w_h, print_w_h, file_size, ext, sale_twd, sale_point', 'safe', 'on'=>'search'),
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
			'single_size_id' => '流水號',
			'single_id' => '圖片編號',
			'size_type' => '尺寸類型',
			'size_description' => '尺寸描述',
			'dpi' => '解析度',
			'mp' => '像素尺寸',
			'w_h' => '圖片尺寸',
			'print_w_h' => '列印尺寸',
			'file_size' => '檔案大小',
			'ext' => '檔案格式',
			'sale_twd' => '銷售價格-台幣',
			'sale_point' => '銷售價格-點數',
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

		$criteria->compare('single_size_id',$this->single_size_id);
		$criteria->compare('single_id',$this->single_id);
		$criteria->compare('size_type',$this->size_type,true);
		$criteria->compare('size_description',$this->size_description,true);
		$criteria->compare('dpi',$this->dpi);
		$criteria->compare('mp',$this->mp,true);
		$criteria->compare('w_h',$this->w_h,true);
		$criteria->compare('print_w_h',$this->print_w_h,true);
		$criteria->compare('file_size',$this->file_size);
		$criteria->compare('ext',$this->ext,true);
		$criteria->compare('sale_twd',$this->sale_twd);
		$criteria->compare('sale_point',$this->sale_point);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Singlesize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
