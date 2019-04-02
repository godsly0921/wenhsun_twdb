<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">新增儀器</h3>
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
<form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('device/newdo');?>" method="post">

    <?php CsrfProtector::genHiddenField(); ?>
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">儀器編號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="codenum" placeholder="請輸入儀器編號" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">儀器中文名稱:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="name" placeholder="請輸入儀器中文名稱" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">儀器英文名稱:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="en_name" name="en_name" placeholder="請輸入儀器英文名稱" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">放置地點:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="position">

                        <?php foreach($groups as $group): ?>

                            <option value="<?=$group->id;?>">
                                <?= $group->name;  ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">目前狀態:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="status">
                        <?php foreach($device_status as $key=>$value): ?>
                                <option value="<?= $value->id ?>">
                                    <?= $value->name ?>
                                </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">購買日期:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="purchase_date" name="purchase_date" placeholder="請輸入儀器購買日期" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">可用年限:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="available_year">
                        <?php foreach($numbers as $key=>$value): ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="detail" class="col-sm-2 control-label">其他注意事項:</label>
                <div class="col-sm-5">
                    <textarea class="form-control" name="attention_item" placeholder="請輸入其他注意事項">
                    </textarea>
                </div>
            </div>


            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">ip位置:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="ip" name="ip" placeholder="請輸入ip位置" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">站號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="station" name="station" placeholder="請輸入站號" value="">
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

<script>
    $( function() {
        $( "#purchase_date").datepicker({ dateFormat: 'yy-mm-dd' }).val();
        $( "#purchase_date" ).datepicker({ dateFormat: 'yy-mm-dd' }).val();
    } );
</script>



