<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">每日出缺勤一覽表</h3>
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
                                <th>員工姓名</th>
                                <th>出勤日</th>
                                <th>首筆出勤紀錄</th>
                                <th>末筆出勤紀錄</th>
                                <th>是否異常</th>
                                <th>說明</th>
                                <th>建立時間</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($model as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <td><?=$value['name']?></td>
                                    <td><?=$value['day']?></td>
                                    <td><?=$value['first_time']?></td>
                                    <td><?=$value['last_time']?></td>
                                    <td><?=$value['abnormal_type']?></td>
                                    <td><?=$value['abnormal']?></td>
                                    <td><?=$value['att_create_at']?></td>
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
