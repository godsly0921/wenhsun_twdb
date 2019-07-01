<?php

/**
 * This is the model class for table "piccolumn".
 *
 * The followings are the available columns in table 'piccolumn':
 * @property integer $piccolumn_id
 * @property string $pic
 * @property string $title
 * @property string $date_start
 * @property string $date_end
 * @property string $time_desc
 * @property string $address
 * @property string $content
 * @property string $recommend_single_id
 * @property string $publish_start
 * @property string $publish_end
 * @property integer $status
 * @property string $update_time
 * @property integer $update_id
 */
class Piccolumn extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'piccolumn';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pic, title, date_start, date_end, time_desc, address, content, publish_start, publish_end, status, update_time, update_id', 'required'),
			array('status, update_id', 'numerical', 'integerOnly'=>true),
			array('pic', 'length', 'max'=>100),
			array('title, time_desc, address, recommend_single_id', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('piccolumn_id, pic, title, date_start, date_end, time_desc, address, content, recommend_single_id, publish_start, publish_end, status, update_time, update_id', 'safe', 'on'=>'search'),
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
			'piccolumn_id' => '流水號',
			'pic' => '小圖',
			'title' => '標題',
			'date_start' => '專欄活動開始日期',
			'date_end' => '專欄活動結束日期',
			'time_desc' => '專欄活動時間',
			'address' => '地址',
			'content' => '專欄內文',
			'recommend_single_id' => 'Recommend Single',
			'publish_start' => '專欄公佈開始時間',
			'publish_end' => '專欄公佈結束時間',
			'status' => '公佈狀態 1:發佈 0:不發佈',
			'update_time' => '更新時間',
			'update_id' => '更新人員',
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

		$criteria->compare('piccolumn_id',$this->piccolumn_id);
		$criteria->compare('pic',$this->pic,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('date_end',$this->date_end,true);
		$criteria->compare('time_desc',$this->time_desc,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('recommend_single_id',$this->recommend_single_id,true);
		$criteria->compare('publish_start',$this->publish_start,true);
		$criteria->compare('publish_end',$this->publish_end,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_id',$this->update_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Piccolumn the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
