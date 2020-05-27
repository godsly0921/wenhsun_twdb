<?php

/**
 * This is the model class for table "api_download".
 *
 * The followings are the available columns in table 'api_download':
 * @property integer $id
 * @property integer $api_manage_id
 * @property integer $image_id
 * @property string $size_type
 * @property string $filename
 * @property string $createtime
 */
class Apidownload extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'api_download';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('api_manage_id, image_id, size_type, filename', 'required'),
			array('api_manage_id, image_id', 'numerical', 'integerOnly'=>true),
			array('size_type', 'length', 'max'=>10),
			array('filename', 'length', 'max'=>100),
			array('createtime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, api_manage_id, image_id, size_type, filename, createtime', 'safe', 'on'=>'search'),
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
			'api_manage_id' => 'Api Manage',
			'image_id' => 'Image',
			'size_type' => 'Size Type',
			'filename' => 'Filename',
			'createtime' => 'Createtime',
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
		$criteria->compare('api_manage_id',$this->api_manage_id);
		$criteria->compare('image_id',$this->image_id);
		$criteria->compare('size_type',$this->size_type,true);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('createtime',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Apidownload the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
