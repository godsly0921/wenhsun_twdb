<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">新增放置地點</h3>
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
<form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('device/positionnewdo');?>" method="post">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">地點名稱:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="cname" name="cname" placeholder="請輸入地點名稱" value="">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">新增</button>
                </div>
            </div>

        </div>
    </div>
</form>


