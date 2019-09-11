<!-- bootstrap-datepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/bootstrap-datepicker.css" rel="stylesheet"/>
<!-- Switchery -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<style type="text/css">
    #single_data .form-group{
        margin: 10px auto;
    }
</style>
<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-body">
        <div class="x_panel">
            <div class="x_title">       
                <h2>圖片修改</h2>
                <div class="clearfix"></div>
            </div>  
            <div class="x_content">
                <form id="single_data" class="form-horizontal form-label-left">
                    <div class="col-lg-6">
                        <img src="<?=$photograph_data['image']?>" width='100%'>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">物件名稱</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="物件名稱" name="object_name" value="<?=$photograph_data['photograph_info']['object_name']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">圖庫編號</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="圖庫編號" readonly="readonly" value="<?=$photograph_data['photograph_info']['single_id']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">原始檔名</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="原始檔名" readonly="readonly" value="<?=$photograph_data['photograph_info']['photo_name']?>">
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
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" class="form-control  has-feedback-left" id="filming_date" name="filming_date" placeholder="圖片年份" value="<?=$photograph_data['photograph_info']['filming_date']?>" aria-describedby="inputSuccess2Status2">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" class="tags form-control" placeholder="攝影年份文字" name="filming_date_text" value="<?=$photograph_data['photograph_info']['filming_date_text']?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">拍攝地點</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="拍攝地點" name="filming_location" value="<?=$photograph_data['photograph_info']['filming_location']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">攝影名稱</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" placeholder="攝影名稱" name="filming_name" value="<?=$photograph_data['photograph_info']['filming_name']?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">保存狀況</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                            <select id="store_status" class="form-control" name="store_status" required>
                                <option value="1" <?=$photograph_data['photograph_info']['store_status'] == 1?"selected":""?>>良好</option>
                                <option value="2" <?=$photograph_data['photograph_info']['store_status'] == 2?"selected":""?>>輕度破損</option>
                                <option value="3" <?=$photograph_data['photograph_info']['store_status'] == 3?"selected":""?>>嚴重破損</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">索引使用限制</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                            <select id="index_limit" class="form-control" name="index_limit" required>
                                <option value="0" <?=$photograph_data['photograph_info']['index_limit'] == 0?"selected":""?>>不開放</option>
                                <option value="1" <?=$photograph_data['photograph_info']['index_limit'] == 1?"selected":""?>>開放</option>
                                <option value="2" <?=$photograph_data['photograph_info']['index_limit'] == 2?"selected":""?>>限制</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">原件使用限制</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                            <select id="original_limit" class="form-control" name="original_limit" required>
                                <option value="0" <?=$photograph_data['photograph_info']['original_limit'] == 0?"selected":""?>>不開放</option>
                                <option value="1" <?=$photograph_data['photograph_info']['original_limit'] == 1?"selected":""?>>開放</option>
                                <option value="2" <?=$photograph_data['photograph_info']['original_limit'] == 2?"selected":""?>>限閱</option>
                                <option value="3" <?=$photograph_data['photograph_info']['original_limit'] == 3?"selected":""?>>限印</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">影像使用限制</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                            <select id="photo_limit" class="form-control" name="photo_limit" required>
                                <option value="0" <?=$photograph_data['photograph_info']['photo_limit'] == 0?"selected":""?>>不開放</option>
                                <option value="1" <?=$photograph_data['photograph_info']['photo_limit'] == 1?"selected":""?>>開放</option>
                                <option value="2" <?=$photograph_data['photograph_info']['photo_limit'] == 2?"selected":""?>>限文訊內部使用</option>
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
                                <select class="select2_multiple form-control" id="category_id" name="category_id[]" multiple="multiple" required>
                                  <?php foreach ($category_data as $key => $value) { ?>
                                    <option value="<?=$value['category_id']?>" <?=in_array($value['category_id'], $photograph_data['photograph_info']['category_id'])?'selected':''?>><?=$value['parents_name']?>_<?=$value['child_name']?></option>
                                  <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">圖片作者</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input id="author" type="text" class="tags form-control" name="author" value="<?=$photograph_data['photograph_info']['author']?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">關鍵字</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input id="keywords" type="text" class="tags form-control" name="keyword" value="<?=$photograph_data['photograph_info']['keyword']?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">內容描述</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea id="description" required="required" class="form-control" name="description"><?=$photograph_data['photograph_info']['description']?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">備註一</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea id="memo1" required="required" class="form-control" name="memo1"><?=$photograph_data['photograph_info']['memo1']?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">備註二</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea id="memo2" required="required" class="form-control" name="memo2"><?=$photograph_data['photograph_info']['memo2']?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="x_panel">
            <div class="x_title">       
                <h2>Source image info</h2>
                <div class="nav navbar-right panel_toolbox form-inline">
                    <div class="form-group">
                        <label>
                          <input type="checkbox" class="js-switch" id="copyright" <?=$photograph_data['photograph_info']['copyright'] == 1?"checked":""?> /> 著作審核通過
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                          <input type="checkbox" class="js-switch" id="publish"  <?=$photograph_data['photograph_info']['publish'] == 1?"checked":""?> /> 上架
                        </label>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" id="single_data_button" style="margin-bottom: 5px !important">圖資修改</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>  
            <div class="x_content">
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
        <form id="single_size_price">
            <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
                <thead>
                <tr role="row">
                    <th>規格名稱</th>
                    <th>尺寸</th>
                    <th>檔案大小</th>
                    <th>點數價格</th>
                    <th>單圖價格</th>               
                </tr>
                </thead>
                <tbody>
                <?php foreach ($photograph_data['size'] as $key => $value) { ?>
                    <tr>
                        <td><?=$value['size_type']?></td>
                        <td><?=$value['w_h']?></td>
                        <td><?=$value['file_size']?></td>
                        <td> <input type="text" class="form-control"  name="sale_point[<?=$value['size_type']?>]" value="<?=$value['sale_point']?>"></td>
                        <td> <input type="text" class="form-control"  name="sale_twd[<?=$value['size_type']?>]" value="<?=$value['sale_twd']?>"></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </form>
        <div class="form-group">
            <button class="btn btn-primary pull-right" id="single_size_price_button" style="margin-bottom: 5px !important">價格修改</button>
        </div>
    </div>
</div>
<!-- bootstrap-datepicker -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/bootstrap-datepicker.js"></script>
<!-- jQuery Tags Input -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<!-- Switchery -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/switchery/dist/switchery.min.js"></script>
<script>
    $(document).ready(function() {

        if(typeof $.fn.tagsInput !== 'undefined'){        
            $('#keywords').tagsInput({
                width: 'auto'
            }); 
            $('#author').tagsInput({
                width: 'auto'
            }); 
        }
        $("#filming_date").datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years"
        });
        $('#single_size_price_button').click(function(){
            var single_size_price = $("#single_size_price").serialize();
            $.ajax({
                type:"POST",
                url: '<?php echo Yii::app()->createUrl('photograph/UpdateSingleSize'); ?>',
                data: {
                    single_size_price:single_size_price,
                    single_id:'<?=$_GET['id']?>'
                },
                success:function(data){
                   result = JSON.parse(data)
                    if(result.status == true){
                        window.location = '<?= Yii::app()->createUrl('photograph/list'); ?>';
                    }else{
                        alert('更新失敗');
                    }
                }
            });
        });
        $('#single_data_button').click(function(){
            var category_id = $('#category_id').val();
            if(!category_id){
                alert('分類項目為必填');
                return;
            }
            var copyright = publish = 0;
            if ($('#copyright').is(':checked')) {
                copyright = 1;
            }
            if ($('#publish').is(':checked')) {
                publish = 1;
            }
            var single_data = $("#single_data").serialize();
            $.ajax({
                type:"POST",
                url: '<?php echo Yii::app()->createUrl('photograph/UpdateSingle'); ?>',
                data: {
                    single_data:single_data,
                    copyright:copyright,
                    publish:publish,
                    single_id:'<?=$_GET['id']?>'
                },
                success:function(data){
                    result = JSON.parse(data)
                    if(result.status == true){
                        window.location = '<?= Yii::app()->createUrl('photograph/list'); ?>';
                    }else{
                        alert('更新失敗');
                    }
                }
            });
        });
    });
</script>