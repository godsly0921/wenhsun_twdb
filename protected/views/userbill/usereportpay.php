<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">使用者帳單查詢</h3>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <!-- 參數選擇 -->
        <!--
        <div class="panel panel-default">
       
            <div class="panel-heading">查詢條件設定</div>
            <div class="panel-body">
                <form action="<?php echo Yii::app() -> createUrl('Userbill/billview/').'/'.$id;?>"  method="POST" >

                    

                    <input type="hidden" name="filter" value="1">
                    
                    <div class="form-group col-md-12">
                        <label for="date_start" class="col-sm-2 control-label">選擇月份:</label>
                        <div class="col-sm-2">
                            <input type="text" id="date_start" name='date_start' class="form-control" >
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

        </div>-->
        <!-- 參數選擇結束 -->

        <div class="panel panel-default">
            <div class="panel-heading col-md-12">
                    
                    <div class='col-md-2'> 
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('userbill/getexcel');?>" method="post">
                    <button type="submit" class="btn btn-default">匯出excel</button>

                    </form>                    
                    </div>
                    
                    <div class='col-md-2'>
                    <a href="<?=Yii::app()->createUrl('userbill/printer');?>"  target="_blank">     
                    <button class="btn btn-default">列印</button>
                    </a> 
                    </div>

                <div class='col-md-2 col-sm-4 col-xs-4'>
                    
                </div>                
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                    <?=$tablecode?>
                    </div>
                </div>

                <div class="row text-center">
                    <?php
                    if(!$isPayOff){
                    ?>
                    <form action="<?php echo Yii::app() -> createUrl('Userbill/billpaydo/').'/'.$id;?>"  method="POST" onsubmit="return confirm('提醒您,進行此動作後帳單紀錄將會付清\n請確實核對所接收之款項');">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">繳費</button>                        
                    </form>
                    <?php
                    }else{
                    ?>
                    <button class='btn btn-lg btn-block btn-danger disabled'>此帳單已結清</button>
                    <?php
                    }
                    ?>

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
            "order": [[4, "desc"], [0, "asc"]],
            "columnDefs": [{"targets": 5, "orderable": false}],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        });
    });
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

<script type="text/javascript">
    $('#date_start').monthpicker({pattern: 'yyyy-mm', 
    selectedYear: 2018,
    startYear: 1900,
    finalYear: 2212,});
    var options = {
    selectedYear: 2015,
    startYear: 2008,
    finalYear: 2018,
    openOnFocus: false // Let's now use a button to show the widget
};
</script>