<?php

/**
 * This is the model class for table "orders_item".
 *
 * The followings are the available columns in table 'orders_item':
 * @property integer $orders_item_id
 * @property string $order_id
 * @property integer $coupon_id
 * @property string $discount
 * @property string $cost_total
 * @property integer $order_category
 * @property integer $product_id
 * @property integer $single_id
 * @property string $size_type
 * @property integer $order_detail_status
 * @property string $memo
 * @property string $memo_create_time
 * @property integer $memo_create_account_id
 */
class Ordersitem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orders_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, cost_total, order_category, order_detail_status', 'required'),
			array('coupon_id, order_category, product_id, single_id, order_detail_status, memo_create_account_id', 'numerical', 'integerOnly'=>true),
			array('order_id', 'length', 'max'=>14),
			array('discount', 'length', 'max'=>2),
			array('cost_total', 'length', 'max'=>6),
			array('size_type', 'length', 'max'=>20),
			array('memo', 'length', 'max'=>200),
			array('memo_create_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('orders_item_id, order_id, coupon_id, discount, cost_total, order_category, product_id, single_id, size_type, order_detail_status, memo, memo_create_time, memo_create_account_id', 'safe', 'on'=>'search'),
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
			'orders_item_id' => '流水號',
			'order_id' => '訂單編號 FK',
			'coupon_id' => '優惠編號 FK',
			'discount' => '折扣金額',
			'cost_total' => '訂單總額',
			'order_category' => '訂單類型 ( 1：點數 2：自由載 3：單圖 )',
			'product_id' => '產品編號 FK ( 若 order_category = 3 不會有資料 )',
			'single_id' => '圖片編號 ( order_category = 3 才會有資料 )',
			'size_type' => '尺寸類型 ( order_category = 3 才會有資料 )',
			'order_detail_status' => '訂單詳細狀態 ( 0：取消訂單 1：已開通 2：已退款 )',
			'memo' => '備註',
			'memo_create_time' => '備註建立時間',
			'memo_create_account_id' => '備註建立人員',
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

		$criteria->compare('orders_item_id',$this->orders_item_id);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('coupon_id',$this->coupon_id);
		$criteria->compare('discount',$this->discount,true);
		$criteria->compare('cost_total',$this->cost_total,true);
		$criteria->compare('order_category',$this->order_category);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('single_id',$this->single_id);
		$criteria->compare('size_type',$this->size_type,true);
		$criteria->compare('order_detail_status',$this->order_detail_status);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('memo_create_time',$this->memo_create_time,true);
		$criteria->compare('memo_create_account_id',$this->memo_create_account_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ordersitem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
