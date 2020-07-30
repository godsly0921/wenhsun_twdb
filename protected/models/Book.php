<?php

/**
 * This is the model class for table "book".
 *
 * The followings are the available columns in table 'book':
 * @property integer $book_id
 * @property integer $single_id
 * @property string $book_num
 * @property integer $category
 * @property string $book_name
 * @property integer $author_id
 * @property string $sub_author_id
 * @property integer $publish_place
 * @property integer $publish_organization
 * @property integer $publish_year
 * @property integer $publish_month
 * @property integer $publish_day
 * @property string $book_version
 * @property integer $book_pages
 * @property string $book_size
 * @property integer $series
 * @property string $summary
 * @property string $memo
 * @property string $create_at
 * @property string $update_at
 * @property string $delete_at
 * @property integer $last_updated_user
 */
class Book extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'book';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('single_id, book_num, category, book_name, author_id, sub_author_id, publish_place, publish_organization, publish_year, book_version, book_pages, book_size, series', 'required'),
			array('single_id, category, author_id, publish_place, publish_organization, publish_year, publish_month, publish_day, book_pages, series, last_updated_user', 'numerical', 'integerOnly'=>true),
			array('book_num', 'length', 'max'=>128),
			array('book_name, book_size', 'length', 'max'=>32),
			array('sub_author_id, book_version', 'length', 'max'=>100),
			array('summary, memo, create_at, update_at, delete_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('book_id, single_id, book_num, category, book_name, author_id, sub_author_id, publish_place, publish_organization, publish_year, publish_month, publish_day, book_version, book_pages, book_size, series, summary, memo, create_at, update_at, delete_at, last_updated_user', 'safe', 'on'=>'search'),
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
			'book_id' => 'Book',
			'single_id' => '"FK，single.single_id 關聯圖庫的圖片"',
			'book_num' => '例如：B0001',
			'category' => '文類 論述、詩、散文、小說、劇本、報導文學、傳記、日記、書信、兒童文學、合集、全集 FK. book_category.category_id',
			'book_name' => 'Book Name',
			'author_id' => '主作家編號 FK. book_author.author_id',
			'sub_author_id' => '次要作者(多選)  FK. book_author.author_id',
			'publish_place' => '出版地 FK. book_publish_place.publish_place_id',
			'publish_organization' => '出版單位｜組織 FK. book_publish_unit.publish_unit_id',
			'publish_year' => '出版日期(年)',
			'publish_month' => '出版日期(月)',
			'publish_day' => '出版日期(日)',
			'book_version' => '版本',
			'book_pages' => '頁數',
			'book_size' => '開本 FK. book_size.book_size_id',
			'series' => '叢書名 FK. book_series.book_series_id',
			'summary' => '簡介',
			'memo' => '備註',
			'create_at' => '建立時間',
			'update_at' => '更新時間',
			'delete_at' => '刪除時間 軟刪除',
			'last_updated_user' => '最後操作者',
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

		$criteria->compare('book_id',$this->book_id);
		$criteria->compare('single_id',$this->single_id);
		$criteria->compare('book_num',$this->book_num,true);
		$criteria->compare('category',$this->category);
		$criteria->compare('book_name',$this->book_name,true);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('sub_author_id',$this->sub_author_id,true);
		$criteria->compare('publish_place',$this->publish_place);
		$criteria->compare('publish_organization',$this->publish_organization);
		$criteria->compare('publish_year',$this->publish_year);
		$criteria->compare('publish_month',$this->publish_month);
		$criteria->compare('publish_day',$this->publish_day);
		$criteria->compare('book_version',$this->book_version,true);
		$criteria->compare('book_pages',$this->book_pages);
		$criteria->compare('book_size',$this->book_size,true);
		$criteria->compare('series',$this->series);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('memo',$this->memo,true);
		// $criteria->compare('create_at',$this->create_at,true);
		// $criteria->compare('update_at',$this->update_at,true);
		// $criteria->compare('delete_at',$this->delete_at,true);
		// $criteria->compare('last_updated_user',$this->last_updated_user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Book the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
