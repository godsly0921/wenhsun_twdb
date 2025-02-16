<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">圖片列表</h3>
       <!-- --><?php /*if ($canCreate === true): */?>
            <a href="<?php echo Yii::app()->createUrl('photograph/new'); ?>" class="btn btn-success btn-right">圖片上傳</a>
       <!-- --><?php /*endif;*/?>
    </div>
</div>

<div class="panel panel-default" style="width:100%; overflow-y:scroll;">
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
                <tr role="row">
                    <th>圖檔編號</th>
                    <th>圖片名稱</th>
                    <th>著作權審核狀態</th>
                    <th>是否上架</th>
                    <th>切圖進度</th>
                    <th>建立時間</th>               
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "processing": true,
            "serverSide": true,  // 啟用 server-side 處理
            "scrollX": true,
            // "stateSave" : true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            },
            "ajax": {
                "url": "<?php echo Yii::app()->createUrl('photograph/ajaxPhotographList');?>",  // 你的資料來源 URL
                "type": "POST",  // 或 POST
                "data": function(d) {
                    // 在發送請求時，可以向後端傳遞額外的參數
                    // d 會包含 DataTables 的默認參數（如 page、length、search等）
                    return {
                        draw: d.draw,  // 用於頁碼控制
                        start: d.start,  // 當前頁的起始索引
                        length: d.length,  // 每頁的數量
                        search: d.search.value  // 搜索條件
                    };
                }
            },
            "columns": [
                { "data": "img_base_info" },
                { "data": "filming_name" },
                { "data": "copyright" },
                { "data": "publish" },
                { "data": "percent" },
                { "data": "create_time" },
                { "data": "edit" }
            ],
            "order": [[ 1, "desc" ]],
        } );
    } );
</script>
<script>
    $(".oprate-del").on('click', function(){
        var id = $(this).data("mem-id");
        var memName = $(this).data("mem-name");
        var answer = confirm("確定要刪除 (" + memName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method',"POST");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('photograph/Delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name' , 'id');
            idInput.setAttribute('value', id);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>