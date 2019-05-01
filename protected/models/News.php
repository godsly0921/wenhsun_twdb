<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property integer $id
 * @property string $new_language
 * @property string $new_title
 * @property string $new_content
 * @property string $new_origin
 * @property string $new_author
 * @property string $image_name
 * @property string $new_image
 * @property string $new_createtime
 * @property integer $new_type
 * @property integer $sort
 * @property integer $builder
 */
class News extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'news';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('new_title,new_content,new_createtime', 'required'),
			array('new_type, sort, builder', 'numerical', 'integerOnly'=>true),
			//array('new_language', 'length', 'max'=>5),
			array('new_title, new_author', 'length', 'max'=>30),
			array('new_origin, image_name, new_image', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, new_title, new_content, new_origin, new_author, image_name, new_image, new_createtime, new_type, sort, builder', 'safe', 'on'=>'search'),
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
			'id' => '主鍵',
			//'new_language' => '語言',
			'new_title' => '標題',
			'new_content' => '內容',
			'new_origin' => '來源或網址',
			'new_author' => '作者',
			'image_name' => '圖片檔名',
			'new_image' => '圖片網址',
			'new_createtime' => '建立時間',
			'new_type' => '是否顯示前台',
			'sort' => '排序',
			'builder' => '建檔人',
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
		//$criteria->compare('new_language',$this->new_language,true);
		$criteria->compare('new_title',$this->new_title,true);
		$criteria->compare('new_content',$this->new_content,true);
		$criteria->compare('new_origin',$this->new_origin,true);
		$criteria->compare('new_author',$this->new_author,true);
		$criteria->compare('image_name',$this->image_name,true);
		$criteria->compare('new_image',$this->new_image,true);
		$criteria->compare('new_createtime',$this->new_createtime,true);
		$criteria->compare('new_type',$this->new_type);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('builder',$this->builder);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return News the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
