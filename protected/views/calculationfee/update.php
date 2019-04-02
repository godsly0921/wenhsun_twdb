<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">儀器基本計費設定 -- 單項修改</h3>
    </div>
</div>

<?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                <li><?= $error[0] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>

<form name="calculationfee_form" class="form-horizontal" method="post"
      action="<?php echo Yii::app()->createUrl('calculationfee/update'); ?>">
    <?php CsrfProtector::genHiddenField(); ?>
    <input type="hidden" name="id" value="<?=$model->id?>">
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <label for="calculationfee_number" class="col-sm-2 control-label">儀器名稱:</label>
                <div class="col-sm-3">
                    <input type="hidden" class="form-control" name="device_id" value="<?= $model -> device_id ?>">
                    <input type="text"  disabled="disabled" class="form-control" value="<?php foreach ($device as $v):?><?php if($model->device_id  == $v->id):?><?php echo trim($v->name) ?><?php endif; ?><?php endforeach; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="calculationfee_name" class="col-sm-2 control-label">第一層名稱:</label>
                <div class="col-sm-3">
                    <input type="hidden" class="form-control" name="level_one_id"
                           placeholder="第一層名稱" value="<?= $model -> level_one_id ?>">
                    <input type="text" class="form-control" name=""
                           disabled="disabled" placeholder="第一層名稱" value="<?php foreach ($level_one_all as $v):?><?php if($model->level_one_id  == $v->id):?><?php echo  trim($v->name) ?><?php endif; ?><?php endforeach; ?>">
                </div>
            </div>


           <!-- <div class="form-group">
                <div class="col-sm-5">
                    <p>計費方式：『 』</p>
                </div>
            </div>
-->
            <div class="form-group">
                <label class="col-sm-2 control-label">基數分鐘 :</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="base_minute"
                           placeholder="每個基數分鐘數"
                           value="<?= $model -> base_minute ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">基數收費 :</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="base_charge"
                           placeholder="每個基數收費"
                           value="<?= $model -> base_charge ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">開機基數</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="start_base_charge"
                           placeholder="開機費基數" value="<?= $model -> start_base_charge ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">最大基數:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="max_use_base"
                           placeholder="機台最大使用基數(輸入 0 表示無上限)" value="<?= $model -> max_use_base ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">預約未使用基數:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="unused_base"
                           placeholder="在原預約時段內延遲開機使用或提早結束使用時未使用之基數每個基數收費" value="<?= $model -> unused_base ?>">
                </div>
            </div>

            <div class="form-calculationfee">
                <div class="col-sm-offset-1 col-sm-10">
                    <button type="submit" class="btn btn-default">修改</button>
                </div>
            </div>

        </div>
    </div>

</form>
