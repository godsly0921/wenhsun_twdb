<?php

/**
 * This is the model class for table "member_favorite".
 *
 * The followings are the available columns in table 'member_favorite':
 * @property integer $member_favorite_id
 * @property integer $member_id
 * @property integer $single_id
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class Memberfavorite extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'member_favorite';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, single_id, create_time', 'required'),
			array('member_id, single_id, status', 'numerical', 'integerOnly'=>true),
			array('update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('member_favorite_id, member_id, single_id, status, create_time, update_time', 'safe', 'on'=>'search'),
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
			'member_favorite_id' => 'Member Favorite',
			'member_id' => '會員編號',
			'single_id' => '圖片編號',
			'status' => '狀態(1:加入 2:移除)',
			'create_time' => '建立時間',
			'update_time' => '更新時間',
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

		$criteria->compare('member_favorite_id',$this->member_favorite_id);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('single_id',$this->single_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Memberfavorite the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
