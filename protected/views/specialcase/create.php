<?php $group_list_session = CJSON::decode(Yii::app()->session['group_list_session_jsons']);?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">特殊狀況新增</h3>
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

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('specialcase/create');?>" method="post" enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField();?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">

                <label for="name" class="col-sm-2 control-label">標題:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="title" placeholder="請輸入標題" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">申請人:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="member_id" placeholder="請輸入申請人" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">申請時間:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control slider_example_2"  name="application_time" placeholder="請輸入申請時間" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">狀況分類:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="category">
                        <?php foreach($category as $key=>$value): ?>
                                <option value="<?= $value->id ?>">
                                    <?= $value->name ?>
                                </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php if(in_array("50",$group_list_session)){?>
            <div class="form-group">
                <label class="col-sm-2 control-label">審核狀態</label>
                <div class="col-sm-5">
                <label class="radio-inline">
                    <input type="radio" name="approval_status" value="0" required>審核通過
                </label>
                <label class="radio-inline">
                    <input type="radio" name="approval_status" value="1" required>審核未通過
                </label>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">審核時間:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control slider_example_2"  name="approval_time" value="" required>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">審核人:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control"  name="approval_account_id" value="" required>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">申請人IP:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control"  name="member_ip" value="" required>
                </div>
            </div>

            <div class="form-group">
                <label for="detail" class="col-sm-2 control-label">詳細訊息:</label>
                <div class="col-sm-5">
                    <textarea class="form-control" name="msg" placeholder="請輸入諮詢資訊" required>

                    </textarea>
                </div>
            </div>
            <?php }?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">新增</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery-ui-timepicker-addon.js">
</script>
<link rel="stylesheet" type="text/css"
      href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/jquery-ui-timepicker-addon.css"/>

<script>
    $(function() {
        if ($('#success_msg').html() != '') {
            $('#success_msg').show().fadeOut(2000)
        }
    })

    $(function() {
        if ($('#error_msg').html() != '') {
            $('#error_msg').show().fadeOut(2000)
        }
    })
</script>
<script>
    $(function () {
        $('.slider_example_2').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'hh:mm:ss',
            stepHour: 1,
            stepMinute: 1,
            stepSecond: 1,
            timezone: "+0800",
        });
    });
</script>
<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>