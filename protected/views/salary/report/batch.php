<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>薪資報表 (<?=$batch_id?>)</h3>
                <button id="send_email" class="btn btn-primary" type="button">寄送薪資郵件</button>
                <button id="export" class="btn btn-primary" type="button">匯出報表</button>
                <a href="<?= Yii::app()->createUrl('/salary/report/export');?>">
                    <button id="export" class="btn btn-default" type="button">返回</button>
                </a>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>選取</th>
                            <th>員工帳號</th>
                            <th>員工姓名</th>
                            <th>部門</th>
                            <th>職務</th>
                            <th>應稅薪資合計</th>
                            <th>薪資合計</th>
                            <th>應扣合計</th>
                            <th>實領薪資</th>
                            <th>狀態</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($list)):?>
                            <?php foreach($list as $data):?>
                                <?php if($data['status'] === 'YET'):?>
                                <tr style="background: #f0c9b4;">
                                <?php else:?>
                                <tr>
                                <?php endif;?>
                                    <td><input class="checked_btn" type="checkbox" name="checked[]" value="<?=$data['id']?>"></td>
                                    <td><?php if (!empty($data['employee_login_id'])):?><?=$data['employee_login_id']?><?php endif;?></td>
                                    <td><?php if (!empty($data['employee_name'])):?><?=$data['employee_name']?><?php endif;?></td>
                                    <td><?php if (!empty($data['employee_department'])):?><?=$data['employee_department']?><?php endif;?></td>
                                    <td><?php if (!empty($data['employee_position'])):?><?=$data['employee_position']?><?php endif;?></td>
                                    <td><?php if (!empty($data['taxable_salary_total'])):?><?=number_format($data['taxable_salary_total'])?><?php endif;?></td>
                                    <td><?php if (!empty($data['salary_total'])):?><?=number_format($data['salary_total'])?><?php endif;?></td>
                                    <td><?php if (!empty($data['deduction_total'])):?><?=number_format($data['deduction_total'])?><?php endif;?></td>
                                    <td><?php if (!empty($data['real_salary'])):?><?=number_format($data['real_salary'])?><?php endif;?></td>
                                    <td>
                                        <?php if (!empty($data['status'])):?>
                                            <?php if ($data['status'] === "OKZ"):?>
                                                已設定
                                            <?php else:?>
                                                尚末設定
                                            <?php endif;?>

                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <a href="<?= Yii::app()->createUrl("/salary/report/employee?id={$data['id']}");?>"><i class="fa fa-edit" style="font-size:18px"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <form id="export_form" action="<?= Yii::app()->createUrl('/salary/report/export');?>" method="POST">
        <?php CsrfProtector::genHiddenField(); ?>
        <input type="hidden" name="batch_id" value="<?=$batch_id?>">
        <input type="submit" style="display:none;">
    </form>
    <script>

        $('#datatable').DataTable({
            "lengthChange": false,
            "paging": true,
            "responsive": true,
            "info": false,
            'iDisplayLength': 30,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁","sNext": "下一頁","sLast": "最後一頁"},
                "sEmptyTable": "查無資料, 快去新增資料吧"
            }
        });

        $("#export").on("click", function(){
            let r = confirm("確認要匯出薪資?");
            if (r === true) {

                let $exportForm = $("#export_form");

                $exportForm.find(".checked_btn").remove();

                $('.checked_btn:checked').each(function() {
                    $(this).clone().hide().appendTo($exportForm);
                });

                $("#export_form").submit();
            }
        });

        $("#send_email").on("click", function(){
            let r = confirm("確認要寄出薪資郵件?");
            if (r === true) {

                $(".lmask").show();

                var checked = [];
                $('.checked_btn:checked').each(function() {
                    checked.push($(this).val());
                });

                var token = $("#_token").prop("value");
                var batchId = "<?=$batch_id?>";
                var request = $.ajax({
                    url: "<?=Yii::app()->createUrl('/salary/report/email'); ?>",
                    method: "POST",
                    data: {"batch_id":batchId, "_token":token, "checked":checked},
                    dataType: "json"
                });

                request.done(function(data) {
                    alert("寄送成功");
                    $(".lmask").hide();
                });

                request.fail(function(jqXHR, textStatus) {
                    $(".lmask").hide();
                    alert(jqXHR.responseJSON.message);
                });
            }
        });
    </script>