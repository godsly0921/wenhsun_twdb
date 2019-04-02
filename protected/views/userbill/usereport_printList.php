<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">各教授學員明細表列印</h3>
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
                                <th>教授姓名</th>
                                <th>學員姓名</th>
                                <th>登入帳號</th>
                                <th>聯絡電話</th>
                                <th>E-mail</th>
                                <th>帳單狀態</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($datas as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <td><?php foreach ($professor as $k => $v): ?>
                                            <?php if($v->id == $value['professor']): ?>
                                                <?= $v->name ?>
                                            <?php endif; ?>
                                        <?php endforeach ?></td>
                                    <td>
                                        <?=$value['name']?>
                                    </td>
                                    <td>
                                        <?=$value['account']?>
                                    </td>
                                    <td>
                                        <?=$value['phone1']?>
                                    </td>
                                    <td><?=$value['email1']?></td>
                                    <td>
                                    <?php
                                    if($value['payoff']== true){
                                    ?>
                                    <span style='color:green;'>已繳清</span> 
                                    <?php
                                    }else{
                                    ?>
                                    <span style='color:red;'>未繳清</span> 
                                    <?php
                                    }
                                    ?>
                                    </td>
                                    
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
