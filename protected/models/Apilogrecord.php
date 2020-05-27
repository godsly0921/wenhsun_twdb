<?php

/**
 * This is the model class for table "api_log_record".
 *
 * The followings are the available columns in table 'api_log_record':
 * @property integer $id
 * @property string $log_format
 * @property string $api_token
 * @property integer $api_manage_id
 * @property string $request
 * @property string $respond
 * @property string $start_time
 * @property string $end_time
 * @property double $total_time
 */
class Apilogrecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'api_log_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('log_format, request, respond, start_time, end_time, total_time', 'required'),
			array('api_manage_id', 'numerical', 'integerOnly'=>true),
			array('total_time', 'numerical'),
			array('log_format', 'length', 'max'=>100),
			array('api_token', 'length', 'max'=>256),
			array('start_time, end_time', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, log_format, api_token, api_manage_id, request, respond, start_time, end_time, total_time', 'safe', 'on'=>'search'),
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
			'log_format' => 'api action',
			'api_token' => 'Api Token',
			'api_manage_id' => 'Api Manage',
			'request' => 'api input',
			'respond' => 'api 執行結果',
			'start_time' => '執行開始時間',
			'end_time' => '執行結束時間',
			'total_time' => '統計執行時間',
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
		$criteria->compare('log_format',$this->log_format,true);
		$criteria->compare('api_token',$this->api_token,true);
		$criteria->compare('api_manage_id',$this->api_manage_id);
		$criteria->compare('request',$this->request,true);
		$criteria->compare('respond',$this->respond,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('total_time',$this->total_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Apilogrecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
