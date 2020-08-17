<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this BookController */
/* @var $model Book */

$this->breadcrumbs=array(
	'Books'=>array('index'),
	$model->book_id,
);

$this->menu=array(
	array('label'=>'List Book', 'url'=>array('index')),
	array('label'=>'Create Book', 'url'=>array('create')),
	array('label'=>'Update Book', 'url'=>array('update', 'id'=>$model->book_id)),
	array('label'=>'Delete Book', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->book_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Book', 'url'=>array('admin')),
);
?>
<?php
	foreach ($session_jsons as $jsons) {
		if ($jsons["power_controller"] == $this->getId() . "/" . $this->getAction()->getId()){
			echo "<h1>".$jsons["power_name"]."</h1>";
			echo "<a href='".Yii::app()->createUrl(Yii::app()->controller->id."/admin")."' class='btn btn-default btn-right'>返回管理頁</a>";
		}
	}

?>
<?php $this->widget('luckywave.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(        
        	'name'=>'流水號',
        	'value'=>$model->book_id,
		),	
		array(        
        	'name'=>'圖庫圖片',
        	'type'=>'raw',
			'value'=>  CHtml::image(DOMAIN."image_storage/P/".$model->single_id.".jpg","",array("style"=>"max-width:150px;max-height:150px")),
        ),
        array(        
        	'name'=>'影像編號',
        	'value'=>$model->book_num,
		),	
		array(        
        	'name'=>'文類',
        	'value'=>$model->category,
		),
		array(        
        	'name'=>'書名',
        	'value'=>$model->book_name,
		),
		array(        
        	'name'=>'主作家',
        	'value'=>$model->author_id,
		),
		array(        
        	'name'=>'次要作家',
        	'value'=>$this->findSubAuthorName($model->sub_author_id),
		),
		array(        
        	'name'=>'出版地',
        	'value'=>$model->publish_place,
		),
		array(        
        	'name'=>'出版單位｜組織',
        	'value'=>$model->publish_organization,
		),
		array(        
        	'name'=>'出版日期(年)',
        	'value'=>$model->publish_year,
		),
		array(        
        	'name'=>'出版日期(月)',
        	'value'=>$model->publish_month,
		),
		array(        
        	'name'=>'出版日期(日)',
        	'value'=>$model->publish_day,
		),
		array(        
        	'name'=>'版本',
        	'value'=>$model->book_version,
		),
		array(        
        	'name'=>'頁數',
        	'value'=>$model->book_pages,
		),
		array(        
        	'name'=>'開本規格',
        	'value'=>$model->book_size,
		),
		array(        
        	'name'=>'叢書名',
        	'value'=>$model->series,
		),
		array(        
        	'name'=>'簡介',
        	'value'=>$model->summary,
		),
		array(        
        	'name'=>'備註',
        	'value'=>$model->memo,
		),
		array(        
        	'name'=>'建立時間',
        	'value'=>$model->create_at,
		),
		array(        
        	'name'=>'更新時間',
        	'value'=>$model->update_at,
		),
		array(        
        	'name'=>'最後操作者',
        	'value'=>$model->last_updated_user,
		)
	),
)); ?>
