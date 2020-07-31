<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3>歷史特休管理</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])): ?>
                    <div id="error_alert" class="alert alert-danger alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::ERR_MSG_KEY];?>
                        <?php unset(Yii::app()->session[Controller::ERR_MSG_KEY]);?>
                    </div>
                <?php endif; ?>
                <div class="x_panel">
                    <form id="form" method="get" action="<?php echo Yii::app()->createUrl('/leave/manager/history_annualLeave_manage'); ?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>
                        <div class="form-group">
                            <div class="col-md-offset-1 col-md-2">
                                <select name="type" id="type" class="form-control" onChange="checkType();">
                                    <option value="1">員工帳號</option>
                                    <option value="2">員工姓名</option>
                                </select>
                            </div>
                            <div id="select1" class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="user_name" name="user_name" class="form-control col-md-7 col-xs-12">
                            </div>
                            <div id="select2" class="col-md-6 col-sm-6 col-xs-12" style="display:none">
                                <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">查詢員工</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="leave" class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">
                        <button id="appove_all" type="submit" onclick="getCheckedCheckboxesFor('leave_appove_group');" class="btn btn-primary">批次結算</button>


                    <table id="datatable1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th><p><input type="checkbox"  name="CheckAll" value="核取方塊" id="CheckAll" />全選/全不選</p></th>
                                <th>姓名</th>
                                <th>開始時間</th>
                                <th>結束時間</th>
                                <th>可請小時數</th>
                                <th>已請小時數</th>
                                <?php if($AnnualLeaveType ==1){ ?>
                                    <th>結算狀態</th>
                                    <th>操作</th>
                                <?php }?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $row) : ?>
                                <tr role="row" id="<?= $row['id'] ?>">
                                    <td>
                                        <?php if($AnnualLeaveType ==1){ ?>
                                            <?php if($row['is_close'] == 0){?>
                                                <input name="leave_appove_group" type="checkbox" value="<?= $row['id'] ?>"/>
                                            <?php }?>
                                        <?php }?>
                                    </td>
                                    <td><?= $row['employee_name'] ?></td>
                                    <td><?= $row['start_date'] ?></td>
                                    <td><?= $row['end_date'] ?></td>
                                    <td><?= $row['leave_available'] ?></td>
                                    <td><?= $row['leave_applied'] ?></td>
                                    <?php if($AnnualLeaveType ==1){ ?>
                                        <td id="<?=$row['id']?>_status"><?= $row['is_close'] ==1?"已結算":"未結算" ?></td>
                                        <td id="<?=$row['id']?>_approve">
                                            <?php if($row['is_close'] == 0){?>
                                                <button type="button" class="btn btn-link" onclick="approve('<?=$row['id']?>')">結算</button>
                                            <?php }?>
                                        </td>
                                    <?php }?>
                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $( function() {
        let availableTags = [<?= $userNameSearchWord ?>];
        $( "#user_name" ).autocomplete({
            source: availableTags
        });

        let availableNameTags = [<?= $nameSearchWord ?>];
        $("#name").autocomplete({
            source: availableNameTags
        });
    } );

    function checkType() {
        if ($("#type").val() == 1) {
            $("#select1").show();
            $("#select2").hide();
            $("#name").val("");
        } else {
            $("#select1").hide();
            $("#select2").show();
            $("#account").val("");
        }
    }

    function approve(id) {
        var values = [];
        values.push(id);
        $.ajax({
            url: "<?= Yii::app()->createUrl('/leave/manager/batchCloseAnnualLeave') ?>",
            type: "POST",
            dataType: "json",
            data: {
                ids: values
            },
            success: function(response) {
                if (response) {
                    alert("批準結算成功");
                    $("#" + id + "_status").html("已結算");
                    $("#" + id + "_approve").html("");
                } else {
                    alert("批準結算失敗");
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("批準結算失敗");
            }
        });
    }

    $(document).ready(function() {
        $("#datatable1").DataTable({
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {
                    "sFirst": "第一頁",
                    "sPrevious": "上一頁",
                    "sNext": "下一頁",
                    "sLast": "最後一頁"
                },
                "sEmptyTable": "無任何資料"
            },
            "order": [
                [1, 'asc']
            ],
            "columnDefs": [
                { "orderable": false, "targets": 0 }
            ]
        });

    });


  function getCheckedCheckboxesFor(checkboxName) {
    var checkboxes = document.querySelectorAll('input[name="' + checkboxName + '"]:checked'), values = [];
    Array.prototype.forEach.call(checkboxes, function(el) {
        values.push(el.value);
    });

    if(values.length == 0){
        alert('尚未選擇');
        return;
    }

    console.log(values);

    if(confirm("確定要批次結算嗎?")){

        $.ajax({
            url: "<?= Yii::app()->createUrl('/leave/manager/batchCloseAnnualLeave') ?>",
            type: "POST",
            dataType: "json",
            data: {
                ids: values
            },
            success: function(response) {
                if (response) {
                    alert("批次結算成功");
                    window.location.reload();
                } else {
                    alert("批次結算失敗:失敗原因"+response);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                 alert("批次結算失敗:失敗原因"+xhr.responseText);
            }
        });
        
    }else{
        alert("已經取消了批次結算操作");
    }



    //return values;
 }

  $(document).ready(function(){
  $("#CheckAll").click(function(){
   if($("#CheckAll").prop("checked")){//如果全選按鈕有被選擇的話（被選擇是true）
    $("input[name='leave_appove_group']").prop("checked",true);//把所有的核取方框的property都變成勾選
   }else{
    $("input[name='leave_appove_group']").prop("checked",false);//把所有的核取方框的property都取消勾選
   }
  })
 })
</script>
