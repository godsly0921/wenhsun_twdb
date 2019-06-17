<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">首頁廣告圖管理</h3>
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
<strong>更新成功!</strong><?=Yii::app()->session['success_msg'];?>
</div>
<?php endif; ?>

<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>


<div class="panel panel-default">
    <div class="panel-heading">首頁廣告圖管理</div>
    <div class="panel-body">
        <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('website/ad_update');?>/<?=$ad->single_id?>" method="post" enctype="multipart/form-data">
            <?php CsrfProtector::genHiddenField(); ?>
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">圖片編號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" readonly id="link" name="link" placeholder="請輸入超連結" value="<?=$ad->single_id?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">圖片:</label>
                <div class="col-sm-5">
                    <img src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL.$ad->single_id?>.jpg">
                </div>
            </div>   
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">排序:</label>
                <div class="col-sm-5">
                    <input type="number" class="form-control" name="sort" placeholder="請輸入排序" value="<?=$ad->sort?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">更新</button>
                </div>
            </div>
        </form>
    </div>        
</div>