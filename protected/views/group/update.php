<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">角色權限修改</h3>
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

<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>

<form name="group_form" class="form-horizontal" method="post" action="<?php echo Yii::app()->createUrl('group/update');?>">
    <?php CsrfProtector::genHiddenField(); ?>
    <input type="hidden" name="group_id" value="<?= $groups->id ?>">
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <label for="group_number"  class="col-sm-2 control-label">角色權重:</label>
                <div class="col-sm-1">
                    <input type="number" min="0" max="255" class="form-control" id="group_number" name="group_number" value="<?=$groups->group_number?>">
                </div>
            </div>

            <div class="form-group">
                <label for="group_name" class="col-sm-2 control-label">角色名稱:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="group_name" name="group_name" placeholder="請輸入群組名稱" value="<?=$groups->group_name?>">
                </div>
            </div>


                <?php foreach($system_checked_list as $system): ?>
                    <?php if($system['system_type'] == 1 ): ?>

                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-10">
                                <div class="checkbox">
                                    <label style="color:red;">
                                        <input type="checkbox" name="group_list[<?=$system['system_number']?>][]" onclick="checkall(<?= $system['system_number']?>, this);" value="<?=$system['system_number']?>" <?=$system['system_checked_type']?>><?= $system['system_name'] ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($system['powers'])): ?>
                            <div class="form-group">
                                <div class="col-sm-offset-1 col-sm-10">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <?php foreach($system['powers'] as $power): ?>
                                                <div class="checkbox-inline">
                                                    <label style="color:#999999">
                                                        <input type="checkbox" value="<?=$power['power_number']?>" name="group_list[<?=$system['system_number']?>][]" <?=$power['power_checked_type']?> ><?=$power['power_name']?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php else: ?>
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="<?=$system['system_number']?>" name="group_list[]" <?=$system['system_checked_type']?> ><?=$system['system_name']?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>


            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                    <button type="submit" class="btn btn-default">修改</button>
                </div>
            </div>

        </div>
    </div>

</form>

<script>
    function checkall(num,maj)
    {
        var tag = 'group_list[' + num +'][]';
        var switch_checked = maj.checked;
        var len = document.group_form.elements.length;
        for (var i = 0; i < len; i++)
        {
            if (document.group_form.elements[i].name == tag)
                document.group_form.elements[i].checked = switch_checked;
        }
    }

    $(function() {
        if ($('#success_msg').html() != '') {
            $('#success_msg').show().fadeOut(2000)
        }
    })
</script>
