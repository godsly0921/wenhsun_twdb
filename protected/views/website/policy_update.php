<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">條款內容管理 - <?=$policy_type[$data->type]?></h3>
        <a href="<?php echo Yii::app()->createUrl('website/policy_list'); ?>" class="btn btn-success btn-right">返回列表頁</a>
    </div>
</div>

<?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                <li><?= $error[0] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if(isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
<div class="alert alert-success">
<strong>新增成功!</strong><?=Yii::app()->session['success_msg'];?>
</div>
<?php endif; ?>

<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>
<div class="panel panel-default">
    <div class="panel-heading">條款內容管理 - <?=$policy_type[$data->type]?></div>
    <div class="panel-body">
        <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('website/policy_update');?>/<?=$data->policy_id?>" method="post" enctype="multipart/form-data">
            <?php CsrfProtector::genHiddenField(); ?>
            <input type="hidden" name="type" value="<?=$data->type?>">
            <div class="form-group row">
                <label class="col-sm-2 control-label">條款內容:</label>
                <div class="col-sm-10">
                    <textarea class="ckeditor" required id="policy_content" name="policy_content"><?=$data->policy_content?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary col-sm-12">修改</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl.'/assets/ckeditor/all/ckeditor.js'; ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        CKEDITOR.replace("policy_content",{
            filebrowserBrowseUrl : "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html';?>",
            filebrowserImageBrowseUrl : "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html?type=Images';?>",
            filebrowserFlashBrowseUrl:"<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html?Type=Flash';?>",
            filebrowserUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';?>",
            filebrowserImageUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';?>",
            filebrowserFlashUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';?>",
        });
        CKEDITOR.config.height = '450px';
    })
</script>