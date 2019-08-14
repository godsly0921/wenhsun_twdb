<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3>查詢員工休假</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])): ?>
                    <div id="error_alert" class="alert alert-danger alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::ERR_MSG_KEY];?>
                        <?php unset(Yii::app()->session[Controller::ERR_MSG_KEY]);?>
                    </div>
                <?php endif; ?>
                <div class="x_panel">
                    <form id="form" method="get" action="<?php echo Yii::app()->createUrl('/leave/manager/hist'); ?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-2">
                                <select name="type" id="type" class="form-control" onChange="checkType();">
                                    <option value="1">員工帳號</option>
                                    <option value="2">員工姓名</option>
                                </select>
                            </div>
                            <div id="select1" class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="user_name" name="user_name" class="form-control col-md-7 col-xs-12">
                            </div>
                            <div id="select2" class="col-md-6 col-sm-6 col-xs-12" style="display:none">
                                <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="year">休假年度</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="year" name="year" placeholder="輸入格式:西元年，例:2019" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">查詢員工</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $( function() {
        let availableTags = [<?= $userNameSearchWord ?>];
        $( "#user_name" ).autocomplete({
            source: availableTags
        });

        let availableNameTags = [<?= $nameSearchWord ?>];
        $("#name").autocomplete({
            source: availableNameTags
        });
    } );

    function checkType() {
        if ($("#type").val() == 1) {
            $("#select1").show();
            $("#select2").hide();
            $("#name").val("");
        } else {
            $("#select1").hide();
            $("#select2").show();
            $("#account").val("");
        }
    }
</script>

