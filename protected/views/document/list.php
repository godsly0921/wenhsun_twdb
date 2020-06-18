<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']);?>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>公文列表</h3>
            </div>

            <?php foreach ($session_jsons as $jsons):?>
                <?php if ($jsons["power_controller"] == 'document/new'):?>
                    <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <a href="<?php echo Yii::app()->createUrl('/document/new'); ?>?document_department=<?=isset($_GET['document_department'])?$_GET['document_department']:'1'?>">
                                    <button class="btn btn-primary" type="button">新增公文</button>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>公文主旨</th>
                            <th>發文字號</th>
                            <th>受文者</th>
                            <th>所屬部門</th>
                            <th>公文類型</th>
                            <th>承辦人</th>
                            <th>存檔代號</th>
                            <th>公文附件</th>
                            <th>更新日期</th>
                            <th>建立日期</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>


                            <?php foreach ($list as $data): ?>
                                <tr role="row">
                                    <td><?= $data->title ?></td>
                                    <td><?= $data->send_text_number ?></td>
                                    <td><?= $data->receiver ?></td>
                                    <td><?= $document_department[$data->document_department] ?></td>
                                    <td><?= $data->d_type->name ?></td>
                                    <td><?= $data->case_officer ?></td>
                                    <td><?= $data->saved_code ?></td>
                                    <td><?= $data->file_name ?></td>
                                    <td><?= $data->update_at ?></td>
                                    <td><?= $data->create_at ?></td>
                                    <td>
                                        <?php foreach ($session_jsons as $jsons):?>
                                            <?php if ($jsons["power_controller"] == 'document/edit'):?>
                                                <a href="<?php echo Yii::app()->createUrl('/document/edit?id=' . $data->id); ?>"><i
                                                        class="fa fa-edit" style="font-size:18px"></i></a>
                                            <?php endif; ?>

                                            <?php if ($jsons["power_controller"] == 'document/download'):?>
                                                <a href="<?php echo Yii::app()->createUrl('/document/download?id=' . $data->id); ?>"
                                                   target="_blank"><i class="fa fa-cloud-download"
                                                                      style="font-size:18px"></i></a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>


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
        });
    </script>
