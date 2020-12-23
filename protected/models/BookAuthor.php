<?php

/**
 * This is the model class for table "book_author".
 *
 * The followings are the available columns in table 'book_author':
 * @property integer $author_id
 * @property integer $single_id
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
 * @property string $original_name
 * @property string $hometown
 * @property string $birth_year
 * @property string $birth_month
 * @property string $bitrh_day
 * @property string $arrive_time
 * @property string $experience
 * @property string $literary_style
 * @property string $literary_achievement
 * @property string $year_of_death
 * @property string $year_of_month
 * @property string $year_of_day
 * @property string $pen_name
 * @property string $literary_genre
 * @property string $present_job
 * @property string $brief_intro
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
			array('single_id, name, summary, status, display_frontend, last_updated_user', 'required'),
			array('single_id, status, display_frontend, last_updated_user', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('gender', 'length', 'max'=>1),
			array('original_name, hometown', 'length', 'max'=>10),
			array('birth_year, year_of_death, year_of_day', 'length', 'max'=>4),
			array('birth_month, birth_day, year_of_month', 'length', 'max'=>2),
			array('arrive_time', 'length', 'max'=>50),
			array('pen_name, literary_genre', 'length', 'max'=>20),
			array('memo, create_at, update_at, delete_at, experience, literary_style, literary_achievement, present_job, brief_intro', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('author_id, single_id, name, gender, summary, memo, create_at, update_at, delete_at, status, display_frontend, last_updated_user, original_name, hometown, birth_year, birth_month, birth_day, arrive_time, experience, literary_style, literary_achievement, year_of_death, year_of_month, year_of_day, pen_name, literary_genre, present_job, brief_intro', 'safe', 'on'=>'search'),
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
			'single_id' => '圖庫圖片',
			'name' => '作者姓名',
			// 'birthday' => '作者生日',
			'gender' => '作者性別',
			'summary' => '作者簡介',
			'memo' => 'Memo',
			'create_at' => '建立時間',
			'update_at' => '更新時間',
			'delete_at' => '刪除時間',
			'status' => '狀態 ( -1:刪除 0:停用 1:啟用 )',
			'display_frontend' => '是否顯示於前台（1：是 0：否）',
			'last_updated_user' => '最後異動的人',
			'original_name' => '本名',
			'hometown' => '籍貫',
			'birth_year' => '出生年',
			'birth_month' => '出生月',
			'birth_day' => '出生日',
			'arrive_time' => '來台時間',
			'experience' => '學經歷',
			'literary_style' => '文學風格',
			'literary_achievement' => '文學成就(得獎經歷)',
			'year_of_death' => '卒年',
			'year_of_month' => '卒月',
			'year_of_day' => '卒日',
			'pen_name' => '筆名',
			'literary_genre' => '創作文類',
			'present_job' => '現職',
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
		$criteria->compare('single_id',$this->single_id);
		$criteria->compare('name',$this->name,true);
		// $criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('delete_at',$this->delete_at,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('display_frontend',$this->display_frontend);		
		$criteria->compare('last_updated_user',$this->last_updated_user);
		$criteria->compare('original_name',$this->original_name,true);
		$criteria->compare('hometown',$this->hometown,true);
		$criteria->compare('birth_year',$this->birth_year,true);
		$criteria->compare('birth_month',$this->birth_month,true);
		$criteria->compare('birth_day',$this->birth_day,true);
		$criteria->compare('arrive_time',$this->arrive_time,true);
		$criteria->compare('experience',$this->experience,true);
		$criteria->compare('literary_style',$this->literary_style,true);
		$criteria->compare('literary_achievement',$this->literary_achievement,true);
		$criteria->compare('year_of_death',$this->year_of_death,true);
		$criteria->compare('year_of_month',$this->year_of_month,true);
		$criteria->compare('year_of_day',$this->year_of_day,true);
		$criteria->compare('pen_name',$this->pen_name,true);
		$criteria->compare('literary_genre',$this->literary_genre,true);
		$criteria->compare('present_job',$this->present_job,true);
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
