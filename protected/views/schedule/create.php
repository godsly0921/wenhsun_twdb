<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">排班新增</h3>
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

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('schedule/create'); ?>" method="post"
      enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

        <div class="panel panel-default">
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label">選擇員工:</label>
                    <div class="col-sm-5">
                        <input type="hidden" name="start_date" value="<?=$_GET['start']?>">
                        <input type="hidden" name="end_date" value="<?=$_GET['end']?>">
                        <?php if($employees != false): ?>
                        <select class="form-control" name="empolyee_id">

                            <?php foreach ($employees as $key => $value): ?>
                                <?php if ($value->id == $empolyee_id): ?>
                                    <option selected="selected" value="<?= $value->id ?>"><?= $value->name ?></option>
                                <?php else: ?>
                                    <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </select>
                        <?php else: ?>
                        <select class="form-control" name="part_time_empolyee_id">
                           <option selected="selected" value="--">員工尚未設定</option>
                        </select>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adv_id" class="col-sm-2 control-label">選擇時段:</label>
                    <div class="col-sm-10">
                        <?php foreach($shift_data as $key => $value){ ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="schedule_shift" id="<?=$value['shift_id']?>" value="<?=$value['shift_id']?>" checked>
                                <label class="form-check-label" for="<?=$value['shift_id']?>">
                                    <?=$value['store_id']?> <?=$value['in_out']?> <?=$value['class']?>時段 <?=$value['time']?>
                                </label>
                            </div>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">送出</button>
                    </div>
                </div>


            </div>
        </div>
    </form>
    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/jquery-ui-timepicker-addon.css"/>

        <script>
        $(function () {
            if ($('#success_msg').html() != '') {
                $('#success_msg').show().fadeOut(2000)
            }
        })

        $(function () {
            if ($('#error_msg').html() != '') {
                $('#error_msg').show().fadeOut(2000)
            }
        })
    </script>
<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>