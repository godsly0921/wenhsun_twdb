<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">門禁異常紀錄表</h3>
    </div>

    <DIV class="col-lg-12" id='btnbox'>
    <FORM>
    <INPUT NAME="print" TYPE="button" VALUE="列印此頁" class='btn '
    ONCLICK="varitext()">
    </FORM>
    </DIV>

</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">


        <div class="panel panel-default">

            <div class="panel-body">
                <style type="text/css">
                #btnbox{
                    margin-bottom: 40px;
                }
                #printtable{
                }
                #printtable >thead th{
                    border:1px solid black;
                    text-align: center;                    
                }
                #printtable td{
                    border:1px solid black;
                    text-align: center;
                }
                </style>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="printtable" width="100%"
                               class="">
                            <thead>
                            <tr role="row">
                                <th>日期</th>
                                <th>使用者</th>
                                <th>門禁分類</th>
                                <th>卡號</th>
                                <th>門禁刷卡時間</th>
                                <th>異常描述</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($model != 0): ?>
                                <?php foreach ($model as $key => $value): ?>
                                    <tr class="gradeC" role="row">
                                        <td><?= $value->date?></td>
                                        <td><?= $value->user_name?></td>
                                        <td><?= $value->station_name?></td>
                                        <td><?= $value->card_number?></td>
                                        <td><?= $value->card_time?></td>
                                        <td><?= $value->exception_description?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
<script type="text/javascript">
    
    $( function() {
        $( "#datepicker_start" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( "#datepicker_end" ).datepicker({ dateFormat: 'yy-mm-dd' });
    });

</script>

<SCRIPT LANGUAGE="JavaScript">
function varitext(text){
//text=document
//print($(".panel-body"))

    var content = $(".panel-body").html();
    var mywindow = window.open('', 'Print', 'height=600,width=800');

    mywindow.document.write('<html><head><title>Print</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');

    mywindow.document.close();
    mywindow.focus()
    mywindow.print();
    mywindow.close();
    return true;
}
</script>
