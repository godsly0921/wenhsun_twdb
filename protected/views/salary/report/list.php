<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>薪資報表 - 批次清單</h3>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <a href="<?= Yii::app()->createUrl('salary/report/new');?>">
                            <button id="new-btn" class="btn btn-primary" type="button">產生薪資報表</button>
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
                            <th>批號</th>
                            <th>更新時間</th>
                            <th>建立時間</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($list)):?>
                            <?php foreach($list as $data):?>
                                <tr>
                                <td><?php if (!empty($data['batch_id'])):?><?=$data['batch_id']?><?php endif;?></td>
                                <td><?php if (!empty($data['update_at'])):?><?=$data['update_at']?><?php endif;?></td>
                                <td><?php if (!empty($data['create_at'])):?><?=$data['create_at']?><?php endif;?></td>
                                <td>
                                    <a href="<?= Yii::app()->createUrl('/salary/report/batch?batchId='.$data['batch_id']);?>"><i class="fa fa-edit" style="font-size:18px"></i></a>
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
