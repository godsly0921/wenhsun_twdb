<link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.10.2/alt/video-js-cdn.css" rel="stylesheet">
<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?><?php
/* @var $this VideoController */
/* @var $model Video */

$this->breadcrumbs=array(
	'Videos'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Video', 'url'=>array('index')),
	array('label'=>'Create Video', 'url'=>array('create')),
	array('label'=>'Update Video', 'url'=>array('update', 'id'=>$model->video_id)),
	array('label'=>'Delete Video', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->video_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Video', 'url'=>array('admin')),
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
		'video_id',
		'name',
		'status',
		'extension',
		'length',
		'file_size',
		array(
			'type' => 'raw',
			'name' => 'm3u8_url',
			'value'=>'<video id="video" class="video-js vjs-default-skin" controls preload="auto" crossorigin="true" width="320" height="240">
                    <source src="'.Yii::app()->createUrl('/') .'/image_storage/video/m3u8/'.$model->m3u8_url.'" type="application/x-mpegURL">
                </video>'
		),
		'description',
		'category',
		'create_at',
		'update_at',
		'delete_at',
		'last_updated_user',
	),
)); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.10.2/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/3.0.2/videojs-contrib-hls.js"></script>
<script type="text/javascript">
 <?php if (!empty($model->m3u8_url)){ ?>
    var myPlayer = videojs('video');
<?php }?>
</script>