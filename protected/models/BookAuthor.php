<?php

/**
 * This is the model class for table "book_author".
 *
 * The followings are the available columns in table 'book_author':
 * @property integer $author_id
 * @property string $name
 * @property string $birthday
 * @property string $gender
 * @property string $summary
 * @property string $memo
 * @property string $create_at
 * @property string $update_at
 * @property string $delete_at
 * @property integer $status
 * @property integer $last_updated_user
 */
class BookAuthor extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'book_author';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, birthday, summary, last_updated_user', 'required'),
			array('status, last_updated_user', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 128),
			array('gender', 'length', 'max' => 1),
			array('original_name, hometown', 'length', 'max' => 10),
			array('birth_year, year_of_death, year_of_day', 'length', 'max' => 4),
			array('birth_month, bitrh_day, year_of_month', 'length', 'max' => 2),
			array('arrive_time', 'length', 'max' => 50),
			array('pen_name, literary_genre', 'length', 'max' => 20),
			array('memo, create_at, update_at, delete_at, delete_at, experience, literary_style, literary_achievement, present_job, brief_intro', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('author_id, name, birthday, gender, summary, memo, create_at, update_at, delete_at, status, last_updated_user, original_name, hometown, birth_year, birth_month, bitrh_day', 'safe', 'on'=>'search'),
			array('birthday', 'type', 'type' => 'date', 'message' => '{attribute}: is not a date!', 'dateFormat' => 'yyyy-MM-dd')
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
			'author_id' => '索引編號',
			'name' => '作者姓名',
			'birthday' => '作者生日',
			'gender' => '作者性別 ( M:男 F:女 )',
			'summary' => '作者簡介',
			'memo' => 'Memo',
			'create_at' => '建立時間',
			'update_at' => '更新時間',
			'delete_at' => '刪除時間',
			'status' => '	狀態 ( -1:刪除 0:停用 1:啟用 )',
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
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('delete_at',$this->delete_at,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('last_updated_user',$this->last_updated_user);
		// var_dump($criteria);exit();
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookAuthor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
