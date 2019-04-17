<!-- Dropzone.js -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/dropzone/dist/min/dropzone.min.css" rel="stylesheet">
<!-- bootstrap-tagsinput.css -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
<!-- bootstrap-fileinput.css -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/css/fileinput.min.css" rel="stylesheet">

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
                    <input id="kv-explorer" type="file" multiple>
                </div>
                </div>
              </div>

              <div class="x_panel">
                <div class="x_title">
                  <h2>整批圖片關鍵字</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_conetne">
                    <input type="text" data-role="tagsinput">
                </div>
              </div>
            </div>
            <div id="step-2">
              <h2 class="StepTitle">Step 2 Content</h2>
              <p>
                do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
              </p>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
              </p>
            </div>
            <div id="step-3">
              <h2 class="StepTitle">Step 3 Content</h2>
              <p>
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
                eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
              </p>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
              </p>
            </div>
            <div id="step-4">
              <h2 class="StepTitle">Step 4 Content</h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
              </p>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
              </p>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
              </p>
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
<!-- Dropzone.js -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/dropzone/dist/min/dropzone.min.js"></script>
<!-- bootstrap-tagsinput.js -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<!-- bootstrap-fileinput.js -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/js/plugins/piexif.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/js/plugins/sortable.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/js/fileinput.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap_fileinput/js/locales/zh-TW.js" type="text/javascript"></script>
<script>
$(document).ready(function () {
$("#kv-explorer").fileinput({
      'theme': 'explorer-fas',
      'uploadUrl': '#',
      overwriteInitial: false,
      initialPreviewAsData: true,
      showBrowse: false,
      initialPreview: [
          
      ],
      initialPreviewConfig: [
          
      ]
  });
})
</script>