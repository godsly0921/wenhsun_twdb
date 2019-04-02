<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">特殊狀況申請</h3>
    </div>
</div>

<div id="error_msg">
    <?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                    <li><?= $error[0] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>


<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>
<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <!-- 參數選擇 -->

        <!-- 參數選擇結束 -->

        <div class="panel panel-default">
            <!--
            <div class="panel-heading col-md-12">
                    
                    <div class='col-md-2'> 
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Devreport/getexcel');?>" method="post">
                    <button type="submit" class="btn btn-default">匯出excel</button>

                    </form>                    
                    </div>
                    
                    <div class='col-md-2'>
                    <a href="<?=Yii::app()->createUrl('Devreport/printer');?>"  target="_blank">     
                    <button class="btn btn-default">列印</button>
                    </a> 
                    </div>

                <div class='col-md-2 col-sm-4 col-xs-4'>
                    
                </div>                
            </div>
            -->

            
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">

                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th>申請人</th>
                                <th>申請原因</th>
                                <th>目前狀態</th>
                                <th>申請日期</th>
                                <th>操作</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rcdata as $key => $value): ?>

                                <?php
                                if($value['status'] == 0){
                                    $tmpstatus = '未審核';
                                }else if( $value['status'] == 1){
                                    $tmpstatus = '審核通過';
                                }else{
                                    $tmpstatus = '審核失敗';
                                }
                                ?>
                                <tr class="gradeC" role="row">
                                    <td><?=$mem[$value['mem_id']]?></td>
                                    <td><?=$value['des']?></td>
                                    <td><?=$tmpstatus?></td>
                                    <td><?=$value['create_date']?></td>
                                    <td><a href="<?php echo Yii::app()->createUrl('Userbill/comfirmform').'/'.$value['id'];?>">
                                        <span class="fa fa-search " aria-hidden="true">
                                        </span></td>
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
                "sEmptyTable": "無任何聯繫資料"
            }
        } );
    } );
    $('#myTable').DataTable({
        responsive: true,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1}
        ]
    });
    $(document).ready(function () {
        $('#calculationfeeTable').DataTable({
            "scrollX": true,
            "lengthChange": true,
            "paging": true,
            "info": true,
            "order": [[1, "desc"], [0, "asc"]],
            "columnDefs": [{"targets": 5, "orderable": false}],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        });
    });
    /*
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
    });*/
</script>

<script type="text/javascript">
    
    $( function() {
        $( "#datepicker_start" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( "#datepicker_end" ).datepicker({ dateFormat: 'yy-mm-dd' });
    });

</script>