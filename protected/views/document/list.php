<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>公文列表</h3>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <a href="/document/new">
                            <button class="btn btn-primary" type="button">新增公文</button>
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
                            <th>公文主旨</th>
                            <th>發文字號</th>
                            <th>受文者</th>
                            <th>公文類型</th>
                            <th>承辦人</th>
                            <th>公文附件</th>
                            <th>更新日期</th>
                            <th>建立日期</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if($list):?>
                                <?php foreach($list as $data):?>
                                    <td>
                                        <a href="/document/edit?id=<?=$data->id?>"><?=$data->title?></a>
                                    </td>
                                    <td><?=$data->send_text_number?></td>
                                    <td><?=$data->receiver?></td>
                                    <td><?=$data->d_type->name?></td>
                                    <td><?=$data->case_officer?></td>
                                    <td>
                                        <a href="/document/download?id=<?=$data->id?>" target="_blank"><?=$data->file_name?></a>
                                    </td>
                                    <td><?=$data->update_at?></td>
                                    <td><?=$data->create_at?></td>
                                <?php endforeach;?>
                            <?php else:?>
                            <td colspan="8">查無資料</td>
                            <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
