<!-- bootstrap-daterangepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<div class="row top_tiles">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
            <div class="count"><?=$count_single_size['total']?></div>
            <h3>目前圖數</h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-comments-o"></i></div>
            <div class="count"><?=$count_single['total']?></div>
            <h3>上圖張數</h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
            <div class="count"><?=$count_single_publish['total']?></div>
            <h3>上架張數</h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
            <div class="count"><?= $count_operation_log?></div>
            <h3>系統操作</h3>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>每日上圖張數統計</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-9 col-sm-12 col-xs-12">
              <div class="demo-container" style="height:280px">
                <div id="system_report" class="demo-placeholder"></div>
              </div>
            </div>

            <div class="col-md-3 col-sm-12 col-xs-12">
              <div>
                <div class="x_title">
                  <h2>Top Profiles</h2>
                  <div class="clearfix"></div>
                </div>
                <ul class="list-unstyled top_profiles scroll-view">
                  <?php foreach ($top_profile as $key => $value) {?>
                    <li class="media event">
                      <div class="media-body">
                        <a class="title" href="#"><?=$value['create_day']?></a>
                        <p><strong>上傳圖數：<?=$value['each_day_count']?>張</strong></p>
                      </div>
                    </li>
                  <?php }?>
                </ul>
              </div>
            </div>
            <div class="col-xl-12">
                <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
                    <thead>
                    <tr role="row">
                        <th>用戶</th>
                        <th>帳號</th>
                        <th>操作</th>
                        <th>Log</th>
                        <th>狀態</th>
                        <th>操作時間</th>               
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- Flot -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/Flot/jquery.flot.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/Flot/jquery.flot.pie.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/Flot/jquery.flot.time.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/Flot/jquery.flot.stack.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/Flot/jquery.flot.resize.js"></script>
<!-- Flot plugins -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/flot.curvedlines/curvedLines.js"></script>
<!-- DateJS -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/DateJS/build/date.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        var s = '<?=$count_eachday_upload?>';
        var chart_plot_data = JSON.parse(s);
        console.log(chart_plot_data);
        var system_data = [];
        for (var i = 0; i < chart_plot_data.length; i++) {
          system_data.push([new Date(chart_plot_data[i][0]).getTime(), chart_plot_data[i][1]]);
        }
        var system_settings = {
            grid: {
                show: true,
                aboveData: true,
                color: "#3f3f3f",
                labelMargin: 10,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 2,
                clickable: true,
                hoverable: true,
                autoHighlight: true,
                mouseActiveRadius: 100
            },
            series: {
                lines: {
                    show: true,
                    fill: true,
                    lineWidth: 2,
                    steps: false
                },
                points: {
                    show: true,
                    radius: 4.5,
                    symbol: "circle",
                    lineWidth: 3.0
                }
            },
            legend: {
                position: "ne",
                margin: [0, -25],
                noColumns: 0,
                labelBoxBorderColor: null,
                labelFormatter: function(label, series) {
                    return label + '&nbsp;&nbsp;';
                },
                width: 40,
                height: 1
            },
            colors: ['#96CA59', '#3F97EB', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'],
            shadowSize: 0,
            tooltip: true,
            tooltipOpts: {
                content: "%s: %y.0",
                xDateFormat: "%d/%m",
            shifts: {
                x: -30,
                y: -50
            },
            defaultTheme: false
            },
            yaxis: {
                min: 0
            },
            xaxis: {
                mode: "time",
                minTickSize: [1, "day"],
                timeformat: "%Y-%m-%d",
                min: system_data[0][0],
                max: system_data[chart_plot_data.length-1][0]
            }
        };  
        if ($("#system_report").length){           
            $.plot( $("#system_report"), 
            [{ 
                label: "上圖張數", 
                data: system_data, 
                lines: { 
                    fillColor: "rgba(150, 202, 89, 0.12)" 
                }, 
                points: { 
                    fillColor: "#fff" } 
            }], system_settings);
            
        }
        $('#specialcaseTable').DataTable( {
            //"scrollX": true,
            "processing": true,
            "serverSide": true,  // 啟用 server-side 處理
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            },
            "ajax": {
                "url": "<?php echo Yii::app()->createUrl('report/ajaxOperationLog');?>",  // 你的資料來源 URL
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
                { "data": "account_name" },
                { "data": "user_account" },
                { "data": "motion" },
                { "data": "log" },
                { "data": "status_text" },
                { "data": "time" }
            ]
        });
    })
</script>