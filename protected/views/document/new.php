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
                <div class="x_panel">
                    <div class="x_title">
                        <h2>新增
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
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="form" method="post" action="<?php echo Yii::app()->createUrl('/document/create'); ?>" data-parsley-validate class="form-horizontal form-label-left" novalidate enctype="multipart/form-data">

                            <?php CsrfProtector::genHiddenField(); ?>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="receiver">受文者</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="receiver" name="receiver" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">發文部門</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="document_department" name="document_department">
                                        <?php if(!empty($document_department)):?>
                                            <?php foreach ($document_department as $key=>$type):?>
                                                <option value="<?=$key?>" <?=(isset($_GET['document_department']) && $_GET['document_department'] == $key)?'selected':''?>><?=$type?></option>
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
                                                <option value="<?=$type['id']?>"><?=$type['name']?></option>
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
                                    <input type="text" id="title" name="title" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="send_text_number">發文字號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="send_text_number" name="send_text_number" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="send_text_date">發文日期</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date" id="send_text_date" name="send_text_date" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="case_officer">承辦人</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="case_officer" name="case_officer" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="case_officer">存檔代號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="saved_code" name="saved_code" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="document_file">公文附件</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="file" id="document_file" name="document_file" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">新增</button>
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