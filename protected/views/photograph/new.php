<!-- bootstrap-datetimepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/jQuery-Smart-Wizard/styles/smart_wizard.css" rel="stylesheet">
<!-- bootstrap-fileinput.css -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/css/fileinput.min.css" rel="stylesheet">
<!-- bootstrap-daterangepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<!-- bootstrap-progressbar -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
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
                    <small>圖片上傳進度</small>
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
                        <input type="text" class="form-control" placeholder="L 台幣" name="twd[L]">
                      </div>
                      <div class="col-lg-6">
                        <label class="control-label">L 點數： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="L 點數" name="point[L]">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-6">
                        <label class="control-label">M 台幣： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="M 台幣" name="twd[M]">
                      </div>
                      <div class="col-lg-6">
                        <label class="control-label">M 點數： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="M 點數" name="point[M]">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-6">
                        <label class="control-label">S 台幣： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="S 台幣" name="twd[S]">
                      </div>
                      <div class="col-lg-6">
                        <label class="control-label">S 點數： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="S 點數" name="point[S]">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-6">
                        <label class="control-label">XL 台幣： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="XL 台幣" name="twd[XL]">
                      </div>
                      <div class="col-lg-6">
                        <label class="control-label">XL 點數： <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="XL 點數" name="point[XL]">
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
                  <h2>圖片上傳進度</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content file_progress">
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
<!-- bootstrap-progressbar -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<script>
$(document).ready(function () {
  var fileupload_count = 0;
  var finishupload_count =0;
  var upload_single_id = [];
  var update_single_ids = '';
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
  
  var fileinput_upload = $("#upload_file").fileinput({
    language: 'zh-TW',  //語言設定
    uploadUrl: "<?php echo Yii::app()->createUrl('photograph/BatUploadFile'); ?>",
    overwriteInitial: false,
    enableResumableUpload: false,
    initialPreviewFileType: 'image',
    initialPreviewAsData: true,
    uploadAsync: true,
    showCancel: false,
    showRemove: false,
    showUpload: false,
    layoutTemplates:{actions:actions},
  }).on("filebatchselected", function(event, files) {
    fileupload_count += Object.keys(files).length;
    fileinput_upload.fileinput("upload");
  }).on('fileuploaded', function(event, data, previewId, index) {
    var response = data.response[0];
    var status = response.status;
    var size = response.fileSize;
    var file_name = response.fileName;
    if(status == true){
      var single_id = response.single_id; 
      upload_single_id.push(single_id);
      finishupload_count++;
      update_progress(index, file_name, size, single_id);
    }else{
      delete_progress(index, file_name, size);
      fileupload_count = fileupload_count -1;
      alert(response.errorMsg);
    }
  }).on('filepreupload', function(event, data, previewId, index) {
    var size = data.files[index].size;
    var file_name = data.files[index].name;
    create_progress(index, file_name, size);
  });

  //在選取檔案開始上傳時建立進度條，進度為0%
  function create_progress( index, filename, filesize ){
    var html = '<div class="row '+ index + '_' + filesize + '">'+
      '<div class="col-xs-4 text-right">'+
        '<span class="file_id_name">檔案名稱：' + filename + '</span>'+
      '</div>'+
      '<div class="col-xs-7">'+
        '<div class="progress progress_sm">'+
          '<div class="progress-bar bg-green" role="progressbar" data-transitiongoal="0" aria-valuenow="0" style="width: 0%;"></div>'+
        '</div>'+
      '</div>'+
      '<div class="col-xs-1 more_info">'+
        '<span class="progress_status">0%</span>'+
      '</div>'+
    '</div>';
    $('.file_progress').append(html);
  }

  //檔案上傳完成後更新進度條進度，進度為100%
  function update_progress(index, filename, filesize, single_id){
    $('.' + index + '_' + filesize + ' .file_id_name').text('檔案名稱：' + filename + ' 編號：' + single_id);
    $('.' + index + '_' + filesize + ' .progress-bar').attr('data-transitiongoal','100');
    $('.' + index + '_' + filesize + ' .progress-bar').attr('aria-valuenow','100');
    $('.' + index + '_' + filesize + ' .progress-bar').attr('style','width: 100%;');
    $('.' + index + '_' + filesize + ' .progress_status').text('100%');
  }

  //檔案上傳失敗移除該檔案的進度條
  function delete_progress( index, filename, filesize ){
    $('.' + index + '_' + filesize).remove();
  }

  $('#wizard').smartWizard({
    cycleSteps:true,
    labelNext: '下一步',
    labelPrevious: '上一步',
    labelFinish: '送出表單',
    onFinish:onFinishCallback,
    //onLeaveStep:onLeaveStepCallback
  });
  function onFinishCallback(){
    if(fileupload_count == finishupload_count){
      update_single_ids = upload_single_id.join();
      photograph_data_submit();
    }else{
      alert('請等圖片全數上傳完畢，再按「送出表單」');
    }
  }
  function onLeaveStepCallback(obj, context){
    console.log("Leaving step "+context.fromStep+" to go to step "+context.toStep);
    return validateSteps(context.fromStep,context.toStep); // return false to stay on step and true to continue navigation  
  }

  function photograph_data_submit(){
    var data = {
      single_data : $("#single_data").serialize(),
      single_size_price:$("#single_size_price").serialize(),
      keywords_data:$("#keywords").val(),
      update_single_ids:update_single_ids
    };
    $.ajax({
      type:"POST",
      url: '<?php echo Yii::app()->createUrl('photograph/PhotographData'); ?>',
      data: data,// serializes the form's elements.
      success:function(data){
         alert(data);// show response from the php script.
      }
    });
  }
  function validateSteps(fromStep,toStep){
    if(toStep == 4){
      return false;
    }else if(fromStep == 2 && toStep == 3){
      $("#wizard").smartWizard('enableStep', 4);
      $('.buttonNext').addClass("buttonDisabled");
      return true;
    }else{
      if($('.buttonNext').hasClass('buttonDisabled')){
        $('.buttonNext').removeClass("buttonDisabled");
      }
      return true;
    }    
  }
})
</script>