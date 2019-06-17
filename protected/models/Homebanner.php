<?php

/**
 * This is the model class for table "home_banner".
 *
 * The followings are the available columns in table 'home_banner':
 * @property integer $home_banner_id
 * @property string $image
 * @property string $link
 * @property string $title
 * @property string $alt
 * @property integer $sort
 * @property string $update_time
 * @property integer $update_account_id
 */
class Homebanner extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'home_banner';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('image, sort, update_time, update_account_id', 'required'),
			array('sort, update_account_id', 'numerical', 'integerOnly'=>true),
			array('image', 'length', 'max'=>100),
			array('link', 'length', 'max'=>512),
			array('title, alt', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('home_banner_id, image, link, title, alt, sort, update_time, update_account_id', 'safe', 'on'=>'search'),
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
			'home_banner_id' => '流水號',
			'image' => 'banner 路徑',
			'link' => '超連結',
			'title' => '圖片標題',
			'alt' => '替代文字',
			'sort' => '排序',
			'update_time' => '更新時間',
			'update_account_id' => '更新人員ID',
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

		$criteria->compare('home_banner_id',$this->home_banner_id);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('alt',$this->alt,true);
		$criteria->compare('sort',$this->sort);
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
	 * @return Homebanner the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
