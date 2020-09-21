<?php

/**
 * This is the model class for table "book_size".
 *
 * The followings are the available columns in table 'book_size':
 * @property integer $book_size_id
 * @property string $name
 * @property integer $status
 * @property string $create_at
 * @property string $update_at
 * @property string $delete_at
 * @property integer $last_updated_user
 */
class BookSize extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'book_size';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('status, last_updated_user', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('book_size_id, name, status, create_at, update_at, delete_at, last_updated_user', 'safe', 'on'=>'search'),
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
			'book_size_id' => '索引編號',
			'name' => '開本規格',
			'status' => '狀態( 0:停用 1:啟用 )',
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
		$criteria->compare('book_size_id',$this->book_size_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('status',$this->status);
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
	 * @return BookSize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
