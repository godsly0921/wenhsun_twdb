<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">角色新增</h3>
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

<form name="group_form" class="form-horizontal" method="post" action="<?php echo Yii::app()->createUrl('group/create');?>">
    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">

            <input type="hidden" class="form-control" id="group_number" name="group_number" placeholder="請輸入權重(數字)" value="100">

            <div class="form-group">
                <label for="group_name" class="col-sm-2 control-label">角色名稱:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="group_name" name="group_name" placeholder="請輸入群組名稱" value="<?php echo Yii::app()->session['group_name']?>">
                </div>
            </div>

            <?php if (!empty($checkList)): ?>
                <?php foreach($checkList as $system): ?>
                    <?php if($system['system_type'] == 1 ): ?>

                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-10">
                                <div class="checkbox">
                                    <label style="color:red;">
                                        <input type="checkbox" name="group_list[<?=$system['system_number']?>][]" onclick="checkall(<?= $system['system_number']?>, this);" value="<?=$system['system_number']?>"><?= $system['system_name'] ?>
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
                                                  <input type="checkbox" value="<?=$power->power_number?>" name="group_list[<?=$system['system_number']?>][]"><?=$power->power_name?>
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
                                        <input type="checkbox" value="<?=$system['system_number']?>" name="group_list[]"/><?=$system['system_name']?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                    <button type="submit" class="btn btn-default">新增</button>
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

<script>	
function checkall(num, maj)
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
</script>





