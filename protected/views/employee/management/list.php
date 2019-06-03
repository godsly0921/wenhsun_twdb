<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']);?>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>員工列表</h3>
            </div>
            <div class="title_right">

                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                    <?php foreach ($session_jsons as $jsons):?>
                    <?php if ($jsons["power_controller"] == 'employee/management/new'):?>

                        <a href="<?= Yii::app()->createUrl('/employee/management/new');?>">
                            <button class="btn btn-primary" type="button">新增員工</button>
                        </a>

                    <?php endif; ?>
                    <?php endforeach; ?>

                    <?php foreach ($session_jsons as $jsons):?>
                    <?php if ($jsons["power_controller"] == 'employee/management/export'):?>
                        <form action="<?= Yii::app()->createUrl('/employee/management/export');?>" method="post" style="display: inline;">
                            <?php CsrfProtector::genHiddenField(); ?>
                            <button id="export_btn" class="btn btn-primary" type="submit">匯出</button>
                        </form>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>帳號</th>
                            <th>姓名</th>
                            <th>分機</th>
                            <th>座位</th>
                            <th>修改時間</th>
                            <th>建立時間</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach($list as $data):?>
                            <tr>
                                <td><?=$data->user_name?></td>
                                <td><?=$data->name?></td>
                                <td><?= isset($data->ext->ext_number) ? $data->ext->ext_number : '未設定分機' ?></td>
                                <td>
                                    <?php if(isset($data->seat->seat_number)):?>
                                        <?=$data->seat->seat_number?>(<?=$data->seat->seat_name?>)
                                    <?php else:?>
                                        未設定座位
                                    <?php endif;?>
                                </td>
                                <td><?=$data->update_at?></td>
                                <td><?=$data->create_at?></td>
                                <td>
                                    <?php foreach ($session_jsons as $jsons):?>
                                        <?php if ($jsons["power_controller"] == 'employee/management/edit'):?>
                                            <a href="<?= Yii::app()->createUrl('/employee/management/edit?id='.$data->id);?>"><i class="fa fa-edit" style="font-size:18px"></i></a>
                                        <?php endif; ?>

                                        <?php if ($jsons["power_controller"] == 'employee/management/contract'):?>
                                            <a href="<?= Yii::app()->createUrl('/employee/management/contract?id='.$data->id);?>" target="_blank" class="print-btn" data-id="<?=$data->id?>"><i class="fa fa-print" style="font-size:18px"></i></a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach;?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){

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

            $("#export_btn").on("click", function(){

                if ($(".dataTables_empty").length === 1) {
                    alert("無資料匯出");
                    return false;
                }

                $("#export_form").remove();
                let exportForm = $("#search_form").clone().appendTo($("body")).hide();
                exportForm.prop("action", "<?= Yii::app()->createUrl('/author/export');?>");
                exportForm.prop("id", "export_form");
                exportForm.submit();
            });
        });
    </script>
