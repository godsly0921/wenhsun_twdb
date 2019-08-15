<script src="<?php echo Yii::app()->request->baseUrl.'/assets/ckeditor/all/ckeditor.js'; ?>"></script>
<!-- bootstrap-daterangepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">圖片專欄管理</h3>
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
<?php if(isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
<div class="alert alert-success">
<strong>新增成功!</strong><?=Yii::app()->session['success_msg'];?>
</div>
<?php endif; ?>

<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>
<div class="panel panel-default">
    <div class="panel-heading">圖片專欄管理</div>
    <div class="panel-body">
        <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('website/piccolumn_update');?>/<?=$piccolumn_data->piccolumn_id?>" method="post" enctype="multipart/form-data">
            <?php CsrfProtector::genHiddenField(); ?>
            <div class="form-group row">
                <label class="col-sm-2 control-label">標題小圖:</label>
                <div class="col-sm-10">
                    <?php if ($piccolumn_data->pic !== ''):;?>
                        <input class="form-control" id="pic" name="pic_old" type="text" value="<?php echo ($piccolumn_data->pic !== NULL) ? $piccolumn_data->pic : '' ?>" readonly>
                        <br>
                        <div><span style="color:red;">圖片長寬需為900*500</span></div>
                        <input type="file" class="form-control-file" id="pic" name="pic" value="<?php echo ($piccolumn_data->pic !== NULL)?$piccolumn_data->pic:'' ?>">
                        <br>
                        <img id="image_pic" src="<?=Yii::app()->createUrl('/') . $piccolumn_data->pic?>" max-width="100%" max-height=100%></td>
                    <?php else:; ?>
                        <input type="file" class="form-control-file" id="pic" name="pic" value="">
                        <div><span style="color:red;">圖片長寬需為900*500</span></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">標題:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="title" name="title" required placeholder="請輸入標題" value="<?=$piccolumn_data->title?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">活動開始日期:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control has-feedback-left datetime" id="date_start" name="date_start" required placeholder="請輸入活動開始日期" value="<?=$piccolumn_data->date_start?>" aria-describedby="inputSuccess2Status2">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">活動結束日期:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control has-feedback-left datetime" id="date_end" name="date_end" required placeholder="請輸入活動結束日期" value="<?=$piccolumn_data->date_end?>" aria-describedby="inputSuccess2Status2">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">活動時間:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="time_desc" name="time_desc" required placeholder="請輸入活動時間" value="<?=$piccolumn_data->time_desc?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">活動地址:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" required id="address" name="address" value="<?=$piccolumn_data->address?>"><?=$piccolumn_data->address?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">發佈開始日期:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control has-feedback-left datetime" id="publish_start" name="publish_start" required placeholder="請輸入發佈開始日期" value="<?=$piccolumn_data->publish_start?>" aria-describedby="inputSuccess2Status2">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">發佈結束日期:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control has-feedback-left datetime" id="publish_end" name="publish_end" required placeholder="請輸入發佈結束日期" value="<?=$piccolumn_data->publish_end?>" aria-describedby="inputSuccess2Status2">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">發佈:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="status" required>                      
                        <option value="1" <?=$piccolumn_data->status==1?"selected":""?>>是</option>
                        <option value="0" <?=$piccolumn_data->status==0?"selected":""?>>否</option>
                    </select>
                </div>
            </div> 
            <div class="form-group row">
                <label class="col-sm-2 control-label">內文:</label>
                <div class="col-sm-10">
                    <textarea class="ckeditor" required id="content" name="content"><?=$piccolumn_data->content?></textarea>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">推薦圖片</div>
                <div class="panel-body">
                    <div class="form-group row">
                        <label for="date_start" class="col-sm-2 control-label"> 圖號:</label>
                        <div class="col-sm-6">
                        <input type="text" class="form-control" id="single_id" name="single_id" placeholder="請輸入圖號" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="date_start" class="col-sm-2 control-label"> 關鍵字:</label>
                        <div class="col-sm-6">
                        <input type="text" class="form-control" id="keyword" name="keyword" placeholder="請輸入關鍵字" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="date_start" class="col-sm-2 control-label"> 分類:</label>
                        <div class="col-sm-6">
                            <select class="select2_multiple form-control" id="category_id" name="category_id[]" multiple="multiple">
                              <?php foreach ($category_data as $key => $value) { ?>
                                <option value="<?=$value['category_id']?>"><?=$value['parents_name']?>_<?=$value['child_name']?></option>
                              <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sort" class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                        <button type="button" class="btn btn-default" id="search_single">查詢</button>
                        </div>
                    </div>
                    <table id="search_single_result" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
                        <thead>
                        <tr role="row">
                            <th></th>
                            <th>圖檔</th>
                            <th>人物資訊</th>
                            <th>事件名稱</th>
                            <th>拍攝時間</th>
                            <th>拍攝地點</th>
                            <th>關鍵字</th>
                        </tr>
                        </thead>
                        <tbody> 

                        </tbody>
                    </table>
                    <div class="panel panel-default">
                        <div class="panel-heading">選取的推薦圖片</div>
                        <div class="panel-body">
                            <table id="select_single" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
                                <thead>
                                    <tr role="row">
                                        <th></th>
                                        <th>圖檔</th>
                                        <th>人物資訊</th>
                                        <th>事件名稱</th>
                                        <th>拍攝時間</th>
                                        <th>拍攝地點</th>
                                        <th>關鍵字</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recommend_single_id_data as $key => $value) {?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" checked value="<?=$value['single_id']?>" onclick="unselect_image(this)"><input type="hidden" value="<?=$value['single_id']?>" name="single_id[]">
                                            </td>
                                            <td><img width="200" src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL.$value['single_id'].".jpg"?>"><center><?=$value['single_id']?></center></td>
                                            <td><?=$value['people_info']?></td>
                                            <td><?=$value['object_name']?></td>
                                            <td><?=$value['filming_date']?></td>
                                            <td><?=$value['filming_location']?></td>
                                            <td><?=$value['keyword']?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary col-sm-12">修改</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->    
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    var search_result = select_single = '';

    function init_datatable(){
        search_result = $('#search_single_result').DataTable( {
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何資料"
            }
        });
    }

    function unselect_image(a){
        select_single.row($(a).parents('tr')).remove().draw();
        search_result.row.add([
            '<input type="checkbox" value="' +$(a).parents('tr').children('td:eq(1)').text()+ '" onclick="select_image(this)"><input type="hidden" value="' +$(a).parents('tr').children('td:eq(1)').text()+ '" name="single_id[]">',
            '<img width="200" src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL?>' +$(a).parents('tr').children('td:eq(1)').text()+ '.jpg"/><br><center>' +$(a).parents('tr').children('td:eq(1)').text()+ '</center>',
            $(a).parents('tr').children('td:eq(2)').text(),
            $(a).parents('tr').children('td:eq(3)').text(),
            $(a).parents('tr').children('td:eq(4)').text(),
            $(a).parents('tr').children('td:eq(5)').text(),
            $(a).parents('tr').children('td:eq(6)').text()
        ]).draw( false );
    }

    function select_image(a){
        search_result.row($(a).parents('tr')).remove().draw();
        select_single.row.add([
            '<input type="checkbox" value="' +$(a).parents('tr').children('td:eq(1)').text()+ '" onclick="unselect_image(this)" checked><input type="hidden" value="' +$(a).parents('tr').children('td:eq(1)').text()+ '" name="single_id[]">',
            '<img width="200" src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL?>' +$(a).parents('tr').children('td:eq(1)').text()+ '.jpg"/><br><center>' +$(a).parents('tr').children('td:eq(1)').text()+ '</center>',
            $(a).parents('tr').children('td:eq(2)').text(),
            $(a).parents('tr').children('td:eq(3)').text(),
            $(a).parents('tr').children('td:eq(4)').text(),
            $(a).parents('tr').children('td:eq(5)').text(),
            $(a).parents('tr').children('td:eq(6)').text(),
        ]).draw( false );
    }

    $(document).ready(function() {
        init_datatable();
        $('.datetime').daterangepicker({
            singleDatePicker: true,
            singleClasses: "picker_2",
            locale: {
                format: 'YYYY-MM-DD'
            }
        }, function(start, end, label) {
            //console.log(start.toISOString(), end.toISOString(), label);
        });
        CKEDITOR.replace("content",{
            filebrowserBrowseUrl : "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html';?>",
            filebrowserImageBrowseUrl : "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html?type=Images';?>",
            filebrowserFlashBrowseUrl:"<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html?Type=Flash';?>",
            filebrowserUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';?>",
            filebrowserImageUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';?>",
            filebrowserFlashUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';?>",
        });

        CKEDITOR.config.height = '450px';
        select_single = $('#select_single').DataTable( {
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何資料"
            }
        });
        $('#search_single').click(function(){
            $.ajax({
                type:"GET",
                url: '<?php echo Yii::app()->createUrl('website/findsingle'); ?>',
                data: {
                    single_id:$('#single_id').val(),
                    category_id:$('#category_id').val(),
                    keyword:$('#keyword').val()
                },
                success:function(data){
                   result = JSON.parse(data)
                    if(result.status == true){
                        var table_row = "";
                        $.each(result.data, function(index, value){
                            table_row += '<tr><td><input type="checkbox" value="' +value.single_id+ '" onclick="select_image(this)"></td><td><img width="200" src="<?=DOMAIN.PHOTOGRAPH_STORAGE_URL?>' +value.single_id+ '.jpg"/><br><center>' +value.single_id+ '</center></td><td>'+value.people_info+'</td><td>'+value.object_name+'</td><td>'+value.filming_date+'</td><td>'+value.filming_location+'</td><td>'+value.keyword.toString()+'</td></tr>';
                        });
                        $('#search_single_result').DataTable().clear().destroy();
                        $('#search_single_result').append(table_row);
                        init_datatable();
                    }
                }
            });
        });
         
    })
</script>