<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>員工列表</h3>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <a href="<?= Yii::app()->createUrl('/employee/management/new');?>">
                            <button class="btn btn-primary" type="button">新增員工</button>
                        </a>
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
                        <?php if(!empty($list)):?>
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
                                        <a href="<?= Yii::app()->createUrl('/employee/management/edit?id='.$data->id);?>"><i class="fa fa-edit" style="font-size:18px"></i></a>
                                        <a href="<?= Yii::app()->createUrl('/employee/management/contract?id='.$data->id);?>" target="_blank" class="print-btn" data-id="<?=$data->id?>"><i class="fa fa-print" style="font-size:18px"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php else:?>
                            <tr><td colspan="7">查無資料, 快去<a href="<?= Yii::app()->createUrl('/employee/management/new');?>">新增資料</a>吧</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
