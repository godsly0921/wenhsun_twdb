<div role="main">
    <div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])): ?>
                    <div id="error_alert" class="alert alert-danger alert-dismissible fade in" role="alert">
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
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            修改
                            <?php if(isset($_GET['document_department'])){
                                switch ($_GET['document_department']) {
                                    case '1':
                                        echo "文訊";
                                        break;
                                    case '2':
                                        echo "基金會";
                                        break;
                                    case '3':
                                        echo "紀州庵";
                                        break;
                                    default:
                                        echo "全部";
                                        break;
                                }
                            }else{
                                echo "全部";
                            }?>
                            公文
                        </h2>
                        <button id="delete-btn" class="btn btn-danger pull-right">刪除</button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="form" method="post" action="<?php echo Yii::app()->createUrl('/document/update'); ?>" data-parsley-validate class="form-horizontal form-label-left" novalidate enctype="multipart/form-data">

                            <?php CsrfProtector::genHiddenField(); ?>
                            <input type="hidden" id="id" name="id" value="<?=$data->id?>">
                            <input type="hidden" id="document_department_hidden" name="document_department_hidden" value="<?=$data->document_department?>">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="receiver">受文者</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="receiver" name="receiver" value="<?=$data->receiver?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">發文部門</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="document_department" name="document_department">
                                        <?php if(!empty($document_department)):?>
                                            <?php foreach ($document_department as $key=>$type):?>
                                                <option value="<?=$key?>" <?=$data->document_department==$key?"selected":""?>><?=$type?></option>
                                            <?php endforeach;?>
                                        <?php else:?>
                                            <option value="">無</option>
                                        <?php endif;?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">公文類型</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="document_type" name="document_type">
                                        <?php if(!empty($documentTypes)):?>
                                            <?php foreach ($documentTypes as $type):?>
                                                <option value="<?=$type['id']?>" <?php if($type->id === $data->document_type):?>selected<?php endif;?>><?=$type['name']?></option>
                                            <?php endforeach;?>
                                        <?php else:?>
                                            <option value="">無公文類型</option>
                                        <?php endif;?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">公文主旨</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="title" name="title" value="<?=$data->title?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="send_text_number">發文字號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="send_text_number" name="send_text_number" value="<?=$data->send_text_number?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="send_text_date">發文日期</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date" id="send_text_date" name="send_text_date" value="<?=$data->send_text_date?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="case_officer">承辦人</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="case_officer" name="case_officer" value="<?=$data->case_officer?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="case_officer">存檔代號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="saved_code" name="saved_code" value="<?=$data->saved_code?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="document_file">公文附件</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="file_name" value="<?=$data->file_name?>" class="form-control col-md-7 col-xs-12" disabled>
                                    <input type="file" id="document_file" name="document_file" style="display: none;">
                                    <label for="document_file" class="btn btn-default" style="margin-top: 5px">更換文件</label>
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button id="submit" type="submit" class="btn btn-primary">修改</button>
                                    <a class="btn btn-default pull-right" href="<?php echo Yii::app()->createUrl('/document'); ?>">返回</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        $("#delete-btn").click(function(){
            let r = confirm("確認要刪除資料?");
            if (r === true) {
                var token = $("#_token").prop("value");
                var id = $("#id").prop("value");
                var document_department = $("#document_department_hidden").prop("value");

                var request = $.ajax({
                    url: "<?=Yii::app()->createUrl('document/delete'); ?>",
                    method: "POST",
                    data: {"id":id, "_token":token},
                    dataType: "json"
                });

                request.done(function(data) {
                    location.href = "<?=Yii::app()->createUrl('document/index?document_department='); ?>" + document_department;
                });

                request.fail(function(jqXHR, textStatus) {
                    alert(jqXHR.responseJSON.message);
                });
            }
        });

        $("#document_file").on("change", function(){
            $("#file_name").val($(this)[0].files[0].name);
        });
    });
</script>