<div class="row">
    <div class="title-wrap col-lg-12">
        <h3>功能修改</h3>
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

<div id="pow_success">
<?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
    <p class="alert alert-success">
        <?php echo Yii::app()->session['success_msg']; ?>
    </p>
<?php endif; ?>
</div>
	
	<div class="panel panel-default">
    <div class="panel-heading">功能修改</div>
    <div class="panel-body">
        <form class="form-horizontal" method="post" action="<?php echo Yii::app()->createUrl('power/update'); ?>">
            <?php CsrfProtector::genHiddenField(); ?>
            <input type="hidden" name="power_id" value="<?= $powers->id ?>">
            <input type="hidden" id="power_number" name="power_number" value="<?= $powers->power_number ?>">

            <div class="form-group">
                <label class="col-sm-2 control-label">功能編號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" value="<?= $powers->power_number ?>" disabled>
                </div>
            </div>

            <div class="form-group">
                <label for="power_name" class="col-sm-2 control-label">功能名稱:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="power_name" name="power_name" placeholder="請輸入功能名稱" value="<?= $powers->power_name ?>">
                </div>
            </div>
            
             <div class="form-group">
                <label for="power_controller" class="col-sm-2 control-label">程式位置:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="power_controller" name="power_controller" placeholder="請輸入程式位置" value="<?= $powers->power_controller ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">系統編號:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="power_master_number">
                        <?php foreach($systems as $system): ?>
                            <?php if($powers->power_master_number == $system->system_number):?>
                                <option selected="selected" value="<?= $system->system_number ?>">
                                    <?= $system->system_name ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $system->system_number ?>">
                                    <?= $system->system_name ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="power_range" class="col-sm-2 control-label">排序:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="power_range" name="power_range" placeholder="請輸入排序" value="<?= $powers->power_range ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">狀態:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="power_display">
                        <?php if ($powers->power_display == 1): ?>
                            <option value="1" selected >顯示</option>
                            <option value="0">隱藏</option>
                        <?php else: ?>
                            <option value="1">顯示</option>
                            <option value="0" selected >隱藏</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">修改</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    $(function() {
        if ($('#pow_success').html() != '') {
            $('#pow_success').show().fadeOut(2000)
        }
    })
</script>
