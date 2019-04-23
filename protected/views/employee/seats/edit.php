<div role="main">
    <div class="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>員工座位編輯</h2><button id="delete-btn" class="btn btn-danger pull-right">刪除</button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="demo-form2" method="post" action="/employee/seats/update" data-parsley-validate class="form-horizontal form-label-left">
                            <?php CsrfProtector::genHiddenField(); ?>
                            <input type="hidden" value="<?=$seat->id?>" id="id" name="id">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seat-name">座位名稱<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="seat-name" name="seat_name" value="<?=$seat->seat_name?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seat-number">座位號碼<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="seat-number" name="seat_number" value="<?=$seat->seat_number?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <a href="/employee/seats"><button type="button" class="btn btn-default">返回</button></a>
                                    <button type="submit" class="btn btn-primary">修改</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])): ?>
        <div id="error-alert" class="alert alert-danger alert-dismissible fade in" role="alert">
            <?php echo Yii::app()->session[Controller::ERR_MSG_KEY];?>
            <?php unset(Yii::app()->session[Controller::ERR_MSG_KEY]);?>
        </div>
    <?php endif; ?>
    <?php if (!empty(Yii::app()->session[Controller::SUCCESS_MSG_KEY])): ?>
        <div id="succ-alert" class="alert alert-success alert-dismissible fade in" role="alert">
            <?php echo Yii::app()->session[Controller::SUCCESS_MSG_KEY];?>
            <?php unset(Yii::app()->session[Controller::SUCCESS_MSG_KEY]);?>
        </div>
    <?php endif; ?>
</div>
<script>
    $(function(){

        $("#delete-btn").click(function(){
            let r = confirm("確認要刪除資料?");
            if (r === true) {
                var token = $("#_token").prop("value");
                var id = $("#id").prop("value");
                var request = $.ajax({
                    url: "<?=Yii::app()->createUrl('employee/seats/delete'); ?>",
                    method: "POST",
                    data: {"id":id, "_token":token},
                    dataType: "json"
                });

                request.done(function(data) {
                    location.href = "<?=Yii::app()->createUrl('employee/seats'); ?>";
                });

                request.fail(function(jqXHR, textStatus) {
                    alert(jqXHR.responseJSON.message);
                });
            }
        });

    });
</script>
