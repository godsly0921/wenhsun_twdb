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
			array('name, birthday, summary', 'required'),
			array('name', 'length', 'max'=>128),
			array('gender', 'length', 'max'=>1),
			array('memo, create_at, update_at, delete_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('author_id, name, birthday, gender, summary, memo, create_at, update_at, delete_at', 'safe', 'on'=>'search'),
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
			'author_id' => '索引編號',
			'name' => '作者姓名',
			'birthday' => '作者生日',
			'gender' => '作者性別(F：小姐 M：先生)',
			'summary' => '作者簡介',
			'memo' => 'Memo',
			'create_at' => '建立時間',
			'update_at' => '更新時間',
			'delete_at' => '刪除時間',
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

		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('delete_at',$this->delete_at,true);

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
