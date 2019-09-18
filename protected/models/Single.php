<?php

/**
 * This is the model class for table "single".
 *
 * The followings are the available columns in table 'single':
 * @property integer $single_id
 * @property string $photo_name
 * @property string $ext
 * @property integer $dpi
 * @property string $color
 * @property integer $direction
 * @property string $author
 * @property integer $photo_source
 * @property string $category_id
 * @property string $filming_date
 * @property string $filming_date_text
 * @property string $filming_location
 * @property string $filming_name
 * @property integer $store_status
 * @property string $people_info
 * @property string $object_name
 * @property string $keyword
 * @property integer $index_limit
 * @property integer $original_limit
 * @property integer $photo_limit
 * @property string $description
 * @property integer $publish
 * @property integer $copyright
 * @property string $memo1
 * @property string $memo2
 * @property string $create_time
 * @property integer $create_account_id
 */
class Single extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'single';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('photo_name, ext, create_time, create_account_id', 'required'),
			array('dpi, direction, photo_source, store_status, index_limit, original_limit, photo_limit, publish, copyright, create_account_id', 'numerical', 'integerOnly'=>true),
			array('photo_name, category_id, filming_date_text, filming_location, filming_name, object_name', 'length', 'max'=>100),
			array('ext', 'length', 'max'=>6),
			array('color', 'length', 'max'=>12),
			array('author, people_info', 'length', 'max'=>256),
			array('filming_date', 'length', 'max'=>4),
			array('keyword, description, memo1, memo2', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('single_id, photo_name, ext, dpi, color, direction, author, photo_source, category_id, filming_date, filming_date_text, filming_location, filming_name, store_status, people_info, object_name, keyword, index_limit, original_limit, photo_limit, description, publish, copyright, memo1, memo2, create_time, create_account_id', 'safe', 'on'=>'search'),
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
			'single_id' => '圖片編號',
			'photo_name' => '圖片原始檔名',
			'ext' => '檔案格式',
			'dpi' => '解析度',
			'color' => '色彩',
			'direction' => '圖片方向( 垂直V=1，水平H=2，正方S=3 )',
			'author' => '作者名稱',
			'photo_source' => '入藏來源',
			'category_id' => '照片類型( 編號 )',
			'filming_date' => '拍攝日期',
			'filming_date_text' => '攝影年份文字說明',
			'filming_location' => '拍攝地點',
			'filming_name' => '攝影名稱',
			'store_status' => '保存狀況(1：良好；2：輕度破損；3：嚴重破損)',
			'people_info' => '人物資訊',
			'object_name' => '物件名稱',
			'keyword' => '圖片關鍵字(用半形逗號區隔)',
			'index_limit' => '索引使用限制(0：不開放；1：開放；2：限制)',
			'original_limit' => '原件使用限制(0：不開放；1：開放；2：限閱；3：限印)',
			'photo_limit' => '影像使用限制(0：不開放；1：開放；2：限文訊內部使用)',
			'description' => '內容描述',
			'publish' => '是否上架(0：否；1：是)',
			'copyright' => '著作權審核狀態(0：不通過；1：通過)',
			'memo1' => '備註一',
			'memo2' => '備註二',
			'create_time' => '上架時間',
			'create_account_id' => '上架人員',
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
		$criteria->compare('photo_name',$this->photo_name,true);
		$criteria->compare('ext',$this->ext,true);
		$criteria->compare('dpi',$this->dpi);
		$criteria->compare('color',$this->color,true);
		$criteria->compare('direction',$this->direction);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('photo_source',$this->photo_source);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('filming_date',$this->filming_date,true);
		$criteria->compare('filming_date_text',$this->filming_date_text,true);
		$criteria->compare('filming_location',$this->filming_location,true);
		$criteria->compare('filming_name',$this->filming_name,true);
		$criteria->compare('store_status',$this->store_status);
		$criteria->compare('people_info',$this->people_info,true);
		$criteria->compare('object_name',$this->object_name,true);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('index_limit',$this->index_limit);
		$criteria->compare('original_limit',$this->original_limit);
		$criteria->compare('photo_limit',$this->photo_limit);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('publish',$this->publish);
		$criteria->compare('copyright',$this->copyright);
		$criteria->compare('memo1',$this->memo1,true);
		$criteria->compare('memo2',$this->memo2,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_account_id',$this->create_account_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Single the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
