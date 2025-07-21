<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<style>
    .preview-table {
        margin-top: 20px;
    }
    .preview-table img {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }
    .export-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0 10px;
    }
    .checkbox-row th {
        text-align: center;
    }
</style>

<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">圖片列表</h3>
       <!-- --><?php /*if ($canCreate === true): */?>
            <a href="<?php echo Yii::app()->createUrl('photograph/new'); ?>" class="btn btn-success btn-right">圖片上傳</a>
       <!-- --><?php /*endif;*/?>
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#exportModal">匯出</a>
        <button class="btn btn-info" data-toggle="modal" data-target="#batchUpdateModal" disabled id="batchUpdateBtn">批次更新</button>
    </div>
</div>

<div class="panel panel-default" style="width:100%; overflow-y:scroll;">
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
                <tr role="row">
                    <th><input type="checkbox" id="chkAll" /></th>
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
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/switchery/dist/switchery.min.js"></script>
<script>
    var selectedIds = new Set();
    var table = null;

    function syncHeaderCheckbox() {
        var rows = table.rows({ page: 'current' }).data().toArray();
        var allChecked = rows.length === 0 ? false : rows.every(function(r){
            return selectedIds.has(String(r.id));
        });
        $('#chkAll').prop('checked', allChecked);
    }

    function updateBatchButtonState() {
        var count = selectedIds.size;
        if (count > 0) $('#batchUpdateBtn').removeAttr('disabled');
        else $('#batchUpdateBtn').attr('disabled', true);
    }

    $(document).ready(function() {
        table = $('#specialcaseTable').DataTable( {
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
            rowId: 'id',
            "columns": [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        var isChecked = selectedIds.has(row.id) ? 'checked' : '';
                        return `<input type="checkbox" class="row-check" value="${row.id}" ${isChecked} />`;
                    }
                },
                { "data": "img_base_info" },
                { "data": "filming_name" },
                { "data": "copyright" },
                { "data": "publish" },
                { "data": "percent" },
                { "data": "create_time" },
                { "data": "edit" }
            ],
            "order": [[ 2, "desc" ]],
            drawCallback: function (settings) {
                syncHeaderCheckbox();
                updateBatchButtonState();
            }
        } );

        $('#specialcaseTable').on('change', 'input.row-check', function() {
            var id = String(this.value);
            if (this.checked) {
                selectedIds.add(id);
            } else {
                selectedIds.delete(id);
                // 取消本頁全選
                $('#chkAll').prop('checked', false);
            }

            updateBatchButtonState();
        });

        $('#chkAll').on('change', function() {
            var checked = this.checked;

            // 只影響當前頁面的行
            var rows = table.rows({ page: 'current' }).nodes();
            $('input.row-check', rows).each(function() {
                $(this).prop('checked', checked).trigger('change'); // 觸發上面事件維護 selectedIds
            });
        });

        $('#batchUpdateBtn').on('click', function() {
            var ids = Array.from(selectedIds);
            if (!ids.length) {
                alert('請先勾選至少一筆資料');
                return;
            }

            // 顯示勾選數量
            $('#batchUpdateCount').text(ids.length);

            // 將 ID 存進 hidden input（後端可拆）
            // 可用逗號，或 JSON。以下用逗號：
            $('#batchUpdateIds').val(ids.join(','));
        });

        $('#batchUpdateForm').on('submit', function(e) {
            e.preventDefault();

            var idsStr = $('#batchUpdateIds').val();
            var ids = idsStr ? idsStr.split(',') : [];

            if (!ids.length) {
                alert('沒有要更新的資料。');
                return;
            }

            // 組 payload：僅加入已啟用的欄位
            var payload = {
                ids: ids,
                category_id: $('#category_id').val(),
                publish: $('#publish').prop('checked') ? 1 : 0,
                keyword: $('#keyword').val(),
                description: $('#description').val()
            };


            $('#batchUpdateWorking').show();

            $.ajax({
                url: "<?php echo Yii::app()->createUrl('photograph/batchUpdate');?>",
                type: "POST",
                data: payload,
                dataType: "json"
            }).done(function(resp){
                $('#batchUpdateWorking').hide();

                if (resp && resp.success) {
                    alert('批次更新完成 (' + resp.updated + ' 筆)。');
                    $('#batchUpdateModal').modal('hide');

                    // 更新表格但停留在原頁
                    table.ajax.reload(null, false);

                    // 若後端更新後這些資料狀態已套用，可以選擇保留勾選或清空
                    selectedIds.clear();
                    updateBatchButtonState();
                    syncHeaderCheckbox();
                } else {
                    alert('批次更新失敗：' + (resp && resp.message ? resp.message : '未知錯誤'));
                }
            }).fail(function(xhr){
                $('#batchUpdateWorking').hide();
                alert('批次更新發生錯誤（' + xhr.status + '）。');
            });
        });

        $('#keyword').tagsInput({
            width: 'auto'
        });

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

<!-- Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 95%; max-width: none">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="exportModalLabel">匯出設定</h4>
            </div>

            <div class="modal-body">
                <!-- 篩選表單 -->
                <form class="form-inline" id="previewForm">
                    <div class="form-group">
                        <label for="typeSelect">種類：</label>
                        <select class="form-control" id="category">
                            <option value="">全部</option>
                            <?php foreach ($categories as $id => $name) { ?>
                            <option value="<?= $id?>"><?= $name?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-info" id="previewBtn">預覽</button>
                </form>

                <!-- 預覽表格 -->
                <div class="preview-table" id="previewSection" style="display: none;">
                    <div class="export-bar">
                        <strong>預覽結果</strong>
                        <button class="btn btn-success" id="exportBtn">匯出</button>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <!-- 欄位選擇列 -->
                        <tr class="checkbox-row">
                            <th><input type="checkbox" name="fields[]" value="url" checked disabled /></th>
                            <th><input type="checkbox" name="fields[]" value="id" checked disabled /></th>
                            <th><input type="checkbox" name="fields[]" value="current_name" checked disabled /></th>
                            <th><input type="checkbox" name="fields[]" value="original_name" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="category" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="persons" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="event" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="date_taken" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="location" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="objects" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="description" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="status" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="source" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="index_limit" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="original_limit" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="photo_limit" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="keyword" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="remark1" checked /></th>
                            <th><input type="checkbox" name="fields[]" value="remark2" checked /></th>
                        </tr>
                        <!-- 表頭 -->
                        <tr>
                            <th>圖片</th>
                            <th>圖庫編號</th>
                            <th>圖片名稱</th>
                            <th>原始檔名</th>
                            <th>圖片分類</th>
                            <th>人物資訊</th>
                            <th>事件名稱</th>
                            <th>拍攝時間</th>
                            <th>拍攝地點</th>
                            <th>物件名稱</th>
                            <th>內容描述</th>
                            <th>保存狀況</th>
                            <th>入藏來源</th>
                            <th>索引使用限制</th>
                            <th>原件使用限制</th>
                            <th>影像使用限制</th>
                            <th>關鍵字</th>
                            <th>備註一</th>
                            <th>備註二</th>
                        </tr>
                        </thead>
                        <tbody id="previewTableBody">
                        <!-- 動態產生 -->
                        </tbody>
                    </table>

                    <nav class="text-right">
                        <ul class="pagination" id="pagination"></ul>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="batchUpdateModal" tabindex="-1" role="dialog" aria-labelledby="batchUpdateModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form id="batchUpdateForm" class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="batchUpdateModalLabel">批次更新 (<span id="batchUpdateCount">0</span> 筆)</h4>
                </div>

                <div class="modal-body">
                    <!-- 隱藏：要更新的多筆 ID (JSON or comma) -->
                    <input type="hidden" name="ids" id="batchUpdateIds">

                    <!-- 選擇要不要更新 publish -->
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            圖片分類
                        </label>
                        <div class="col-sm-8">
                            <select id="category_id" name="category_id[]" class="form-control" required multiple>
                                <?php foreach ($categories as $id => $name) { ?>
                                <option value="<?= $id?>"><?= $name?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <!-- 選擇要不要更新 percent -->
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            上架
                        </label>
                        <div class="col-sm-8">
                            <input type="checkbox" name="publish" id="publish" class="js-switch" value="1" checked />
                        </div>
                    </div>

                    <!-- 選擇要不要更新 percent -->
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            關鍵字
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="keyword" id="keyword" class="form-control" />
                        </div>
                    </div>

                    <!-- 選擇要不要更新 percent -->
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            描述
                        </label>
                        <div class="col-sm-8">
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <span id="batchUpdateWorking" class="pull-left text-muted" style="display:none;">處理中...</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">套用更新</button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- Loading 遮罩 -->
<div id="loadingOverlay" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255,255,255,0.7);
    z-index: 1050;
    text-align: center;
">
    <div class="spinner" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%)">
        <span class="glyphicon glyphicon-refresh glyphicon-spin" style="font-size: 40px;"></span>
        <p>資料載入中...</p>
    </div>
</div>


<!-- Scripts -->
<script>
    var currentPage = 1;
    var lastPage = 1;

    function renderTable(data) {
        var tbody = $('#previewTableBody');
        tbody.empty();

        if (data.length === 0) {
            tbody.html('<tr><td colspan="19" class="text-center text-muted">沒有資料</td></tr>');
            return;
        }

        data.forEach(function (item) {
            var row = `
                <tr>
                    <td><img src="${item.url}" /></td>
                    <td>${item.id}</td>
                    <td>${item.current_name}</td>
                    <td>${item.original_name}</td>
                    <td>${item.category}</td>
                    <td>${item.persons}</td>
                    <td>${item.event}</td>
                    <td>${item.date_taken}</td>
                    <td>${item.location}</td>
                    <td>${item.objects}</td>
                    <td>${item.description}</td>
                    <td>${item.status}</td>
                    <td>${item.source}</td>
                    <td>${item.index_limit}</td>
                    <td>${item.original_limit}</td>
                    <td>${item.photo_limit}</td>
                    <td>${item.keyword}</td>
                    <td>${item.remark1}</td>
                    <td>${item.remark2}</td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    function renderPagination(current, total) {
        var pagination = $('#pagination');
        pagination.empty();

        if (total <= 1) return;

        var maxVisible = 8;
        var startPage = Math.max(1, current - Math.floor(maxVisible / 2));
        var endPage = startPage + maxVisible - 1;

        if (endPage > total) {
            endPage = total;
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        // 上一頁
        var prevClass = current === 1 ? 'disabled' : '';
        pagination.append(`<li class="${prevClass}"><a href="#" data-page="${current - 1}">&laquo;</a></li>`);

        // 中間頁碼
        for (var i = startPage; i <= endPage; i++) {
            var active = current === i ? 'active' : '';
            pagination.append(`<li class="${active}"><a href="#" data-page="${i}">${i}</a></li>`);
        }

        // 下一頁
        var nextClass = current === total ? 'disabled' : '';
        pagination.append(`<li class="${nextClass}"><a href="#" data-page="${current + 1}">&raquo;</a></li>`);
    }

    function fetchPage(page) {
        var category = $('#previewForm').find('#category').val();

        showLoading(); // <--- 顯示 Loading

        $.ajax({
            url: '<?= Yii::app()->createUrl('photograph/preview') ?>',
            type: 'POST',
            data: {
                category: category,
                page: page
            },
            success: function (res) {
                currentPage = res.current_page;
                lastPage = res.last_page;

                renderTable(res.data);
                renderPagination(currentPage, lastPage);
                $('#previewSection').show();
            },
            error: function () {
                alert('讀取資料失敗');
            },
            complete: function () {
                hideLoading(); // <--- 結束時關閉 Loading（不論成功或錯誤）
            }
        });
    }

    function showLoading() {
        $('#loadingOverlay').show();
    }

    function hideLoading() {
        $('#loadingOverlay').hide();
    }

    // 點擊預覽 → 請求第 1 頁
    $('#previewBtn').on('click', function () {
        fetchPage(1);
    });

    // 點擊分頁按鈕
    $(document).on('click', '#pagination a', function (e) {
        e.preventDefault();
        var page = parseInt($(this).data('page'));
        if (!isNaN(page) && page >= 1 && page <= lastPage && page !== currentPage) {
            fetchPage(page);
        }
    });

    // 匯出按鈕
    $('#exportBtn').on('click', function () {
        const category = $('#category').val();
        const fields = [];

        $('input[name="fields[]"]:checked').each(function () {
            fields.push($(this).val());
        });

        const params = new URLSearchParams();
        params.append('category', category);
        fields.forEach(f => params.append('fields[]', f));

        const url = '<?= Yii::app()->createUrl('photograph/export') ?>?' + params.toString();

        // 使用 window.open 開啟新視窗以觸發下載
        window.open(url, '_blank');
    });
</script>

