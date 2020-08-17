<?php

/**
 * This is the model class for table "video".
 *
 * The followings are the available columns in table 'video':
 * @property integer $video_id
 * @property string $name
 * @property integer $status
 * @property string $extension
 * @property integer $length
 * @property integer $file_size
 * @property string $m3u8_url
 * @property string $description
 * @property integer $category
 * @property string $create_at
 * @property string $update_at
 * @property string $delete_at
 * @property integer $last_updated_user
 */
class Video extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'video';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('extension, length, file_size, m3u8_url, description', 'required'),
			array('status, length, file_size, last_updated_user', 'numerical', 'integerOnly'=>true),
			array('name, m3u8_url, category', 'length', 'max'=>100),
			array('extension', 'length', 'max'=>4),
			array('create_at, update_at, delete_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('video_id, name, status, extension, length, file_size, m3u8_url, description, category, create_at, update_at, delete_at, last_updated_user', 'safe', 'on'=>'search'),
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
			'_Account' => array(self::BELONGS_TO, 'Account', 'last_updated_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'video_id' => 'Video',
			'name' => '影片名稱',
			'status' => '狀態( 0：停用 1：啟用 )',
			'extension' => '副檔名',
			'length' => '影片長度(秒)',
			'file_size' => '檔案大小(KB)',
			'm3u8_url' => '影音碎檔',
			'description' => '影片描述',
			'category' => '分類',
			'create_at' => '建立時間',
			'update_at' => '更新時間',
			'delete_at' => '刪除時間',
			'last_updated_user' => '最後異動的人',
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
		$criteria->addCondition("status<>-1");
		$criteria->compare('video_id',$this->video_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('extension',$this->extension,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('file_size',$this->file_size);
		$criteria->compare('m3u8_url',$this->m3u8_url,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('category',$this->category);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('delete_at',$this->delete_at,true);
		$criteria->compare('last_updated_user',$this->last_updated_user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Video the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
