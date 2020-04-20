<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">新增 API</h3>
    </div>
</div>

<?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <?=Yii::app()->session['error_msg']?>
    </div>
<?php endif; ?>
<?php if(isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
    <div class="alert alert-success">
        <strong><?=Yii::app()->session['success_msg'];?></strong>
    </div>
<?php endif; ?>
<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>
<form name="group_form" class="form-horizontal" method="post" action="<?= empty($data['id'])?Yii::app()->createUrl('apimanage/apimanage_create'):Yii::app()->createUrl('apimanage/apimanage_update/'.$data['id']);?>">
    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <<input type="hidden" name="id" value="<?=$data['id']?>">
            <div class="form-group">
                <label for="group_name" class="col-lg-2 control-label">API KEY:</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" id="api_key" name="api_key" readonly value="<?=$data['api_key']?>">
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-lg-2 control-label">API 密碼:</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" id="api_password" name="api_password" readonly value="<?=$data['api_password']?>">
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-lg-2 control-label">狀態:</label>
                <div class="col-lg-6">
                    <select class="form-control" name="status">
                        <option value="1" <?= $data['status']==1?"selected":"" ?>>啟用</option>
                        <option value="0" <?= $data['status']==0?"selected":"" ?>>停用</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="group_name" class="col-lg-2 control-label">備註:</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" id="remark" name="remark" value="<?=$data['remark']?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-6">
                    <button type="submit" class="btn btn-default"><?=empty($data['id'])?"新增":"修改"?></button>
                </div>
            </div>

        </div>
    </div>

</form>

<script>
	$(function() {
		if ($('#hide_message').html() != '') {
			$('#hide_message').show().fadeOut(3500)
		}
	})
</script>