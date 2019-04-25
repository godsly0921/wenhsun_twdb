<!-- bootstrap-daterangepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-body">
        <div class="x_panel">
            <div class="x_title">       
                <h2>圖片修改</h2>
                <div class="clearfix"></div>
            </div>  
            <div class="x_conetne">
                <div class="col-lg-6">
                    <img src="<?=$photograph_data['image']?>" width='100%'>
                </div>
                <div class="col-lg-6">
                    <form class="form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">物件名稱</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="物件名稱" name="object_name" value="<?=$photograph_data['photograph_info']['object_name']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">圖庫編號</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="圖庫編號" name="single_id" readonly="readonly" value="<?=$photograph_data['photograph_info']['single_id']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">原始檔名</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="原始檔名" name="photo_name" readonly="readonly" value="<?=$photograph_data['photograph_info']['photo_name']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">圖片描述</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="圖片描述" name="description" value="<?=$photograph_data['photograph_info']['description']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">圖片人物</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="圖片人物" name="people_info" value="<?=$photograph_data['photograph_info']['people_info']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">圖片年份</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control  has-feedback-left" id="filming_date" name="filming_date" placeholder="圖片年份" value="<?=$photograph_data['photograph_info']['filming_date']?>" aria-describedby="inputSuccess2Status2">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">圖片場景</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="圖片場景" name="filming_location" value="<?=$photograph_data['photograph_info']['filming_location']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">場景名稱</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="場景名稱" name="filming_name" value="<?=$photograph_data['photograph_info']['filming_name']?>">
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
                        <!-- <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">供圖者</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="供圖者" value="<?#=$photograph_data['photograph_info']['object_name']?>">
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">圖片分類</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="select2_multiple form-control" name="category_id[]" multiple="multiple" required>
                                  <?php foreach ($category_data as $key => $value) { ?>
                                    <option value="<?=$value['category_id']?>" <?=in_array($value['category_id'], $photograph_data['photograph_info']['category_id'])?'selected':''?>><?=$value['parents_name']?>_<?=$value['child_name']?></option>
                                  <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">關鍵字</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input id="keywords" type="text" class="tags form-control" name="keyword" value="<?=$photograph_data['photograph_info']['keyword']?>" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="x_panel">
            <div class="x_title">       
                <h2>Source image info</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>               
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Settings 1</a></li>
                            <li><a href="#">Settings 2</a></li>
                        </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>  
            <div class="x_conetne">
                <table class="table table-bordered">
                    <tr>
                        <th>Filesize</th>
                        <td><?=$photograph_data['source']['file_size']?></td>
                        <th>Format</th>
                        <td><?=$photograph_data['source']['ext']?></td>
                    </tr>
                    <tr>
                        <th>Number pixels</th>
                        <td><?=$photograph_data['source']['mp']?></td>
                        <th>Geometry</th>
                        <td><?=$photograph_data['source']['w_h']?></td>
                    </tr>
                    <tr>
                        <th>Resolution</th>
                        <td><?=$photograph_data['source']['dpi']?></td>
                        <th>Colorspace</th>
                        <td><?=$photograph_data['source']['color']?></td>
                    </tr>
                    <tr>
                        <th>Print size</th>
                        <td><?=$photograph_data['source']['print_w_h']?></td>
                        <th></th>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>圖檔編號</th>
                <!-- <th>分類</th> -->
                <th>著作權審核狀態</th>
                <th>是否上架</th>
                <th>切圖進度</th>
                <th>建立時間</th>               
                <th>操作</th>
            </tr>
            </thead>
            <tbody> 

            </tbody>
        </table>
    </div>
</div>
<!-- bootstrap-daterangepicker -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->    
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<!-- jQuery Tags Input -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        if(typeof $.fn.tagsInput !== 'undefined'){        
            $('#keywords').tagsInput({
                width: 'auto'
            }); 
        }
        $('#filming_date').daterangepicker({
            singleDatePicker: true,
            singleClasses: "picker_2",
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
</script>