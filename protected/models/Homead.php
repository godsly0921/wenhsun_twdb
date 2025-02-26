<?php

/**
 * This is the model class for table "home_ad".
 *
 * The followings are the available columns in table 'home_ad':
 * @property integer $single_id
 * @property integer $sort
 * @property string $update_time
 * @property integer $update_account_id
 */
class Homead extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'home_ad';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('single_id, sort, update_time, update_account_id', 'required'),
			array('single_id, sort, update_account_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('single_id, sort, update_time, update_account_id', 'safe', 'on'=>'search'),
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
			'single_id' => '圖號',
			'sort' => '排序',
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

		$criteria->compare('single_id',$this->single_id);
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
	 * @return Homead the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
