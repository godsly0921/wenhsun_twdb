<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">門禁記錄統計表</h3>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <!-- 參數選擇 -->
        <div class="panel panel-default">
            <div class="panel-heading">查詢條件設定</div>
            <div class="panel-body">
                <form action="<?php echo Yii::app() -> createUrl('doorcount/report'); ?>"  method="POST" >
                    <input type="hidden" name="filter" value="1">
                    <div class="form-group col-md-12">
                        <label for="date_start" class="col-sm-2 control-label">開始日期:</label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control" id="datepicker_start" name="date_start" placeholder="請匯出開始日期" >
                        </div>

                        <label for="date_end" class="col-sm-2 control-label">結束日期:</label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control" id="datepicker_end" name="date_end" placeholder="請匯出結束日期">
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="sort" class="col-sm-2 control-label"> 教授姓名: </label>
                        <div class="col-sm-6">
                            <select class="form-control" id='professor' name='professor'>
                                <option value="" selected="selected">---請選擇---</option>
                                <?php foreach ($professor as $key => $value): ?>
                                    <option value="<?=$value->id?>"><?=$value->name?></option>
                                <?php endforeach ?>
                            </select>
                      </div>
                    </div>



                    <div class="form-group col-md-12">
                        <label for="sort" class="col-sm-2 control-label"> 單位: </label>

                        <div class="col-sm-6">
                        <?php foreach ($grp as $key => $value): ?>
                        <label class="checkbox-inline">
                            <input type="checkbox" name='grp[]' id="inlineCheckbox1" value="<?=$value->id?>"><?=$value->name?>
                        </label>
                        <?php endforeach ?>

                        </div>
                    </div>

                    <div class="form-group col-md-12 ">

                        <label for="sort" class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                        <button type="submit" class="btn btn-default">查詢</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <!-- 參數選擇結束 -->

        <div class="panel panel-default">
            <div class="panel-heading col-md-12">
                    
                    <div class='col-md-2'> 
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('doorcount/getexcel');?>" method="post">
                    <button type="submit" class="btn btn-default">匯出excel</button>

                    </form>                    
                    </div>
                    
                    <div class='col-md-2'>
                    <a href="<?=Yii::app()->createUrl('doorcount/printer');?>"  target="_blank">     
                    <button class="btn btn-default">列印</button>
                    </a> 
                    </div>

                <div class='col-md-2 col-sm-4 col-xs-4'>
                    
                </div>                
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th>使用者姓名</th>
                                <th>卡號</th>
                                <th>教授姓名</th>
                                <th>刷卡次數</th>
                                <th>總金額</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rcdata as $key => $value): ?>
                                <tr class="gradeC" role="row">

                                    <td><?=$value['username']?></td>
                                    <td><?=$value['cardnum']?></td>
                                    <td><?=$value['pname']?></td>
                                    <td><?=$value['usetime']?></td>
                                    <td><?=$value['use_price']?></td>
                                    
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script
    src="https://blackrockdigital.github.io/startbootstrap-sb-admin-2/vendor/datatables-responsive/dataTables.responsive.js"></script>
<script>
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何門禁資料"
            }
        } );
    } );


    $(".oprate-del").on('click', function () {
        var calculationfeeId = $(this).data("calculationfee-id");
        var answer = confirm("確定要刪除 (" + calculationfeeId + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('calculationfee/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', calculationfeeId);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>
<script type="text/javascript">
    
    $( function() {
        $( "#datepicker_start" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( "#datepicker_end" ).datepicker({ dateFormat: 'yy-mm-dd' });
    });

</script>