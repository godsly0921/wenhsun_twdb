<!-- bootstrap-fileinput.css -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/css/fileinput.min.css" rel="stylesheet">
<!-- bootstrap-daterangepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<!-- page content -->
<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>圖片上傳</h3>
    </div>
  </div>
  <div class="clearfix"></div>

  <div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_content">
          <!-- Smart Wizard -->
          <div id="wizard" class="form_wizard wizard_horizontal">
            <ul class="wizard_steps">
              <li>
                <a href="#step-1">
                  <span class="step_no">1</span>
                  <span class="step_descr">
                    Step 1<br />
                    <small>圖片上傳並建立關鍵字</small>
                  </span>
                </a>
              </li>
              <li>
                <a href="#step-2">
                  <span class="step_no">2</span>
                  <span class="step_descr">
                    Step 2<br />
                    <small>建立圖片資訊</small>
                  </span>
                </a>
              </li>
              <li>
                <a href="#step-3">
                  <span class="step_no">3</span>
                  <span class="step_descr">
                    Step 3<br />
                    <small>圖片價格設定</small>
                  </span>
                </a>
              </li>
              <li>
                <a href="#step-4">
                  <span class="step_no">4</span>
                  <span class="step_descr">
                    Step 4<br />
                    <small>切圖進度</small>
                  </span>
                </a>
              </li>
            </ul>
            <div id="step-1">
              <div class="x_panel">
                <div class="x_title">
                  <h2>拖曳上傳圖片</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="file-loading">
                    <input id="upload_file" type="file" multiple name="file">
                </div>
                </div>
              </div>

              <div class="x_panel">
                <div class="x_title">
                  <h2>整批圖片關鍵字</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_conetne">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <input id="keywords" type="text" class="tags form-control" name="keyword" value="" />
                  </div>
                </div>
              </div>
            </div>
            <div id="step-2">
              <h2 class="StepTitle">圖片上架(全釋資料)</h2>
              <div class="x_panel">
                <div class="x_conetne">
                  <form id="single_data" data-parsley-validate class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">照片類型 <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="select2_multiple form-control" name="category_id[]" multiple="multiple" required>
                          <?php foreach ($category_data as $key => $value) { ?>
                            <option value="<?=$value['category_id']?>" <?=$key==0?'selected':''?>><?=$value['parents_name']?>_<?=$value['child_name']?></option>
                          <?php }?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="single_cal2">攝影日期
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control has-feedback-left" id="single_cal2" name="filming_date" placeholder="攝影日期" aria-describedby="inputSuccess2Status2">
                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">拍攝地點</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="filming_location" placeholder="拍攝地點">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">攝影名稱</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="filming_name" placeholder="攝影名稱">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">保存狀況</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="store_status" class="form-control" name="store_status" required>
                          <option value="1">良好</option>
                          <option value="2">輕度破損</option>
                          <option value="3">嚴重破損</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">人物資訊</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="people_info" placeholder="人物資訊">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">物件名稱</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="object_name" placeholder="物件名稱">
                      </div>
                    </div>
                     <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">索引使用限制</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="index_limit" class="form-control" name="index_limit" required>
                          <option value="0">不開放</option>
                          <option value="1">開放</option>
                          <option value="2">限制</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">原件使用限制</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="original_limit" class="form-control" name="original_limit" required>
                          <option value="0">不開放</option>
                          <option value="1">開放</option>
                          <option value="2">限閱</option>
                          <option value="3">限印</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">影像使用限制</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="photo_limit" class="form-control" name="photo_limit" required>
                          <option value="0">不開放</option>
                          <option value="1">開放</option>
                          <option value="2">限文訊內部使用</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">內容描述</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="description" required="required" class="form-control" name="description"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">備註一</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="memo1" required="required" class="form-control" name="memo1"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">備註二</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="memo2" required="required" class="form-control" name="memo2"></textarea>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div id="step-3">
              <h2 class="StepTitle">圖片上架(定價)</h2>
              <div class="x_panel">
                <div class="x_conetne">
                  <form id="single_size_price" data-parsley-validate class="form-horizontal form-label-left">
                    <div class="form-group">
                      <div class="col-lg-6">
                        <label class="control-label">L 台幣： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="L 台幣" name="twd[l]">
                      </div>
                      <div class="col-lg-6">
                        <label class="control-label">L 點數： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="L 點數" name="point[l]">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-6">
                        <label class="control-label">M 台幣： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="M 台幣" name="twd[m]">
                      </div>
                      <div class="col-lg-6">
                        <label class="control-label">M 點數： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="M 點數" name="point[m]">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-6">
                        <label class="control-label">S 台幣： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="S 台幣" name="twd[s]">
                      </div>
                      <div class="col-lg-6">
                        <label class="control-label">S 點數： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="S 點數" name="point[s]">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-6">
                        <label class="control-label">XL 台幣： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="XL 台幣" name="twd[xl]">
                      </div>
                      <div class="col-lg-6">
                        <label class="control-label">XL 點數： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="XL 點數" name="point[xl]">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-6">
                        <label class="control-label">Source 台幣： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="Source 台幣" name="twd[source]">
                      </div>
                      <div class="col-lg-6">
                        <label class="control-label">Source 點數： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="Source 點數" name="point[source]">
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div id="step-4">
              <div class="x_panel">
                <div class="x_title">
                  <h2>切圖進度</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                </div>
              </div>
            </div>
          </div>
          <!-- End SmartWizard Content -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
<!-- jQuery Smart Wizard -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->    
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<!-- jQuery Tags Input -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
<!-- bootstrap-fileinput.js -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/js/plugins/piexif.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/js/plugins/sortable.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/js/fileinput.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/js/locales/zh-TW.js" type="text/javascript"></script>
<script>
$(document).ready(function () {
  if(typeof $.fn.tagsInput !== 'undefined'){        
    $('#keywords').tagsInput({
      width: 'auto'
    }); 
  }
  var actions='<div class="file-actions">\n' +
        '    <div class="file-footer-buttons">\n' +
        '        {delete}' +
        '    </div>\n' +
        '    {drag}\n' +
        '    <div class="clearfix"></div>\n' +
        '</div>';
  var data = {
    single_data : $("#single_data").serialize(),
    single_size_price:$("#single_size_price").serialize(),
    keywords_data:$("#keywords").val()
  };
  var plugin = $("#upload_file").fileinput({
    language: 'zh-TW',  //語言設定
    uploadUrl: "<?php echo Yii::app()->createUrl('photograph/fileupload'); ?>",
    overwriteInitial: false,
    enableResumableUpload: true,
    initialPreviewAsData: true,
    uploadAsync: true,
    showCancel: false,
    showRemove: false,
    showUpload: false,
    layoutTemplates:{actions:actions},
    uploadExtraData:function() {
      var ExtraData = {
        single_data : $("#single_data").serialize(),
        single_size_price:$("#single_size_price").serialize(),
        keywords_data:$("#keywords").val()
      };
      return ExtraData;
    },
  }).on('fileuploaded', function(event, data, previewId, index) {
    var index = index.split("_");
    var filename = ''
    for (i = 1; i < index.length; i++) {
      filename += index[i];
    }
    console.log(filename);
  });

  $('#wizard').smartWizard({
    onFinish:onFinishCallback
  });
  function onFinishCallback(){
    console.log(plugin.initialPreview); // get initialPreview
    $('#upload_file').fileinput('upload');
  } 
})
</script>